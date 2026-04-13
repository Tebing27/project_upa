<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\FieldValue;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateLegacyCmsCommand extends Command
{
    protected $signature = 'cms:migrate-legacy {--force : Jalankan ulang meskipun sudah pernah dijalankan}';

    protected $description = 'Migrasi data CMS dari skema lama (sections/content_blocks) ke skema baru (page_sections/section_fields/field_values)';

    public function handle(): int
    {
        if (! $this->option('force') && AppSetting::query()->where('key', 'cms_legacy_migrated')->where('value', '1')->exists()) {
            $this->error('Migrasi sudah pernah dijalankan. Gunakan --force untuk menjalankan ulang.');

            return self::FAILURE;
        }

        $this->info('Memulai migrasi data CMS legacy...');

        $this->migrateHomePage();
        $this->migrateProfil();
        $this->migrateKontak();
        $this->migrateMedia();

        AppSetting::query()->updateOrCreate(
            ['key' => 'cms_legacy_migrated'],
            ['value' => '1']
        );

        $this->info('✅ Migrasi selesai. Flag cms_legacy_migrated disimpan di app_settings.');

        return self::SUCCESS;
    }

    private function migrateHomePage(): void
    {
        $page = Page::query()->where('slug', 'home')->first();

        if (! $page) {
            $this->warn('Halaman home tidak ditemukan, skip.');

            return;
        }

        $sections = DB::table('sections')
            ->join('section_types', 'section_types.id', '=', 'sections.section_type_id')
            ->where('sections.page_id', $page->id)
            ->orderBy('sections.sort_order')
            ->select('sections.*', 'section_types.name as type_name')
            ->get();

        foreach ($sections as $section) {
            $blocks = DB::table('content_blocks')
                ->join('block_types', 'block_types.id', '=', 'content_blocks.block_type_id')
                ->where('content_blocks.section_id', $section->id)
                ->orderBy('content_blocks.sort_order')
                ->select('content_blocks.*', 'block_types.name as block_type_name')
                ->get();

            $sortOrder = $section->sort_order;
            $typeName = $section->type_name;

            // Hero section (sort_order=1, type=hero)
            if ($sortOrder === 1 && $typeName === 'hero') {
                $this->migrateBlocksToFields($page, 'hero', [
                    1 => 'hero_title',
                    2 => 'hero_subtitle',
                    3 => 'cta_text',
                    4 => 'cta_link',
                ], $blocks, imageFieldKey: 'hero_image');

                continue;
            }

            // Intro/video section (sort_order=3) — ambil youtube_url dari block ke-3
            if ($sortOrder === 3) {
                $this->migrateBlocksToFields($page, 'video', [
                    3 => 'youtube_url',
                ], $blocks);

                continue;
            }

            // Settings/whatsapp section (sort_order=8) — asumsikan block 1 = wa_number
            if ($sortOrder === 8) {
                $this->migrateBlocksToFields($page, 'whatsapp', [
                    1 => 'wa_number',
                    2 => 'wa_message',
                ], $blocks);

                continue;
            }
        }

        $this->info('✓ Home page migrated.');
    }

    private function migrateProfil(): void
    {
        $page = Page::query()->where('slug', 'profil')->first();

        if (! $page) {
            return;
        }

        $blocks = $this->firstSectionBlocks($page->id);
        $this->migrateBlocksToFields($page, 'content', [
            1 => 'profil_text',
        ], $blocks, imageFieldKey: 'profil_image');

        $this->info('✓ Profil page migrated.');
    }

    private function migrateKontak(): void
    {
        $page = Page::query()->where('slug', 'kontak')->first();

        if (! $page) {
            return;
        }

        $blocks = $this->firstSectionBlocks($page->id);
        $this->migrateBlocksToFields($page, 'content', [
            1 => 'alamat',
            2 => 'telepon',
            3 => 'email',
            4 => 'maps_embed',
        ], $blocks);

        $this->info('✓ Kontak page migrated.');
    }

    private function migrateMedia(): void
    {
        foreach (['media', 'instagram', 'youtube', 'facebook'] as $slug) {
            $page = Page::query()->where('slug', $slug)->first();

            if (! $page) {
                continue;
            }

            $blocks = $this->firstSectionBlocks($page->id);

            $mapping = match ($slug) {
                'media' => [1 => 'instagram_url', 2 => 'youtube_url', 3 => 'facebook_url'],
                'instagram' => [1 => 'instagram_handle', 2 => 'embed_url'],
                'youtube' => [1 => 'channel_url', 2 => 'channel_name'],
                'facebook' => [1 => 'page_url', 2 => 'page_name'],
                default => [],
            };

            $this->migrateBlocksToFields($page, 'content', $mapping, $blocks);

            $this->info("✓ {$slug} page migrated.");
        }
    }

    /**
     * @param  Collection<int, object>  $blocks
     * @param  array<int, string>  $sortOrderToFieldKey  Map block sort_order → field_key
     */
    private function migrateBlocksToFields(
        Page $page,
        string $sectionKey,
        array $sortOrderToFieldKey,
        $blocks,
        ?string $imageFieldKey = null
    ): void {
        $pageSection = PageSection::query()
            ->where('page_id', $page->id)
            ->where('section_key', $sectionKey)
            ->first();

        if (! $pageSection) {
            $this->warn("  PageSection '{$sectionKey}' untuk page '{$page->slug}' tidak ditemukan, skip.");

            return;
        }

        foreach ($blocks as $block) {
            $sortOrder = $block->sort_order;

            // Text blocks
            if ($block->block_type_name === 'text' && isset($sortOrderToFieldKey[$sortOrder])) {
                $fieldKey = $sortOrderToFieldKey[$sortOrder];
                $textValue = DB::table('text_contents')
                    ->where('content_block_id', $block->id)
                    ->value('value');

                if ($textValue === null) {
                    continue;
                }

                $sectionField = SectionField::query()
                    ->where('page_section_id', $pageSection->id)
                    ->where('field_key', $fieldKey)
                    ->first();

                if (! $sectionField) {
                    $this->warn("  Field '{$fieldKey}' tidak ditemukan di database, skip.");

                    continue;
                }

                FieldValue::query()->updateOrCreate(
                    ['section_field_id' => $sectionField->id],
                    ['value' => $textValue, 'media_file_id' => null]
                );
            }

            // Image blocks
            if ($block->block_type_name === 'image' && $imageFieldKey !== null) {
                $imageContent = DB::table('image_contents')
                    ->where('content_block_id', $block->id)
                    ->first();

                if (! $imageContent || ! $imageContent->media_file_id) {
                    continue;
                }

                $sectionField = SectionField::query()
                    ->where('page_section_id', $pageSection->id)
                    ->where('field_key', $imageFieldKey)
                    ->first();

                if (! $sectionField) {
                    continue;
                }

                FieldValue::query()->updateOrCreate(
                    ['section_field_id' => $sectionField->id],
                    ['value' => null, 'media_file_id' => $imageContent->media_file_id]
                );
            }
        }
    }

    /**
     * @return Collection<int, object>
     */
    private function firstSectionBlocks(int $pageId): Collection
    {
        $sectionId = DB::table('sections')
            ->where('page_id', $pageId)
            ->orderBy('sort_order')
            ->value('id');

        if (! $sectionId) {
            return collect();
        }

        return DB::table('content_blocks')
            ->join('block_types', 'block_types.id', '=', 'content_blocks.block_type_id')
            ->where('content_blocks.section_id', $sectionId)
            ->orderBy('content_blocks.sort_order')
            ->select('content_blocks.*', 'block_types.name as block_type_name')
            ->get();
    }
}
