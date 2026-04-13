<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FaqPage extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $faqContent = [];

    public function mount(): void
    {
        $this->faqContent = $this->loadFaqContent();
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.faq-page', [
            'pageTitle' => $this->faqContent['faq_title'] ?? 'Pusat Bantuan & FAQ',
            'pageSubtitle' => $this->faqContent['faq_subtitle'] ?? 'Temukan jawaban atas pertanyaan yang sering diajukan seputar registrasi dan proses sertifikasi.',
            'searchPlaceholder' => $this->faqContent['faq_search_placeholder'] ?? 'Cari pertanyaan... (misal: pendaftaran, sertifikat)',
            'tabs' => $this->faqTabs(),
            'faqs' => $this->faqItems(),
            'helpTitle' => $this->faqContent['faq_help_title'] ?? 'Masih butuh bantuan?',
            'helpText' => $this->faqContent['faq_help_text'] ?? 'Tim operasional UPA LUK siap membantu Anda pada jam kerja.',
            'helpButtonText' => $this->faqContent['faq_help_button_text'] ?? 'Hubungi Kami',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadFaqContent(): array
    {
        $page = Page::query()
            ->where('slug', 'faq')
            ->with(['pageSections.fields.value.mediaFile'])
            ->first();

        if (! $page) {
            return [];
        }

        $content = [];
        $content['faq_items'] = [];

        foreach ($page->pageSections as $section) {
            foreach ($section->fields as $field) {
                $value = $field->isImage()
                    ? ($field->value?->imageUrl() ?? null)
                    : ($field->value?->value ?? null);

                if ($value !== null && $value !== '') {
                    $content[$field->field_key] = $value;
                }

                if ($section->section_key === 'faq_items' && preg_match('/^(faq_[a-z0-9]+)_([a-z]+)$/i', $field->field_key, $matches)) {
                    $prefix = $matches[1];
                    $subKey = $matches[2];

                    if ($value) {
                        $content['faq_items'][$prefix][$subKey] = $value;
                    }
                }
            }
        }

        return $content;
    }

    /**
     * @return array<int, string>
     */
    private function faqTabs(): array
    {
        $configuredTabs = collect([
            $this->faqContent['faq_category_1'] ?? 'Pendaftaran',
            $this->faqContent['faq_category_2'] ?? 'Asesmen',
            $this->faqContent['faq_category_3'] ?? 'Sertifikat',
            $this->faqContent['faq_category_4'] ?? 'Lainnya',
        ])
            ->map(fn (?string $item): string => trim((string) $item))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return ['Semua', ...$configuredTabs];
    }

    /**
     * @return array<int, array{category: string, question: string, answer: string}>
     */
    private function faqItems(): array
    {
        $items = Collection::make($this->faqContent['faq_items'] ?? [])
            ->map(function (array $item): ?array {
                $question = trim((string) ($item['question'] ?? ''));
                $answer = trim((string) ($item['answer'] ?? ''));

                if ($question === '' || $answer === '') {
                    return null;
                }

                return [
                    'category' => trim((string) ($item['category'] ?? 'Lainnya')) ?: 'Lainnya',
                    'question' => $question,
                    'answer' => $answer,
                ];
            })
            ->filter()
            ->values()
            ->all();

        if ($items !== []) {
            return $items;
        }

        return [
            [
                'category' => 'Pendaftaran',
                'question' => 'Bagaimana cara mendaftar uji kompetensi di UPA LUK?',
                'answer' => 'Pendaftaran dilakukan secara online melalui portal ini. Anda perlu membuat akun, memverifikasi alamat email, melengkapi biodata profil, lalu memilih skema sertifikasi yang tersedia di halaman Dasbor Anda.',
            ],
            [
                'category' => 'Pendaftaran',
                'question' => 'Apa saja dokumen persyaratan yang dibutuhkan?',
                'answer' => 'Secara umum, Anda memerlukan KTP, Ijazah terakhir atau transkrip nilai, pas foto formal, dan portofolio bukti kompetensi sesuai skema yang dipilih.',
            ],
            [
                'category' => 'Asesmen',
                'question' => 'Di mana lokasi pelaksanaan uji kompetensi?',
                'answer' => 'Pelaksanaan asesmen umumnya dilakukan di Tempat Uji Kompetensi UPN Veteran Jakarta, dengan beberapa skema tertentu dapat menggunakan TUK sewaktu.',
            ],
            [
                'category' => 'Sertifikat',
                'question' => 'Berapa lama proses penerbitan sertifikat BNSP?',
                'answer' => 'Setelah dinyatakan kompeten, proses pencetakan dan distribusi sertifikat biasanya membutuhkan waktu estimasi 2 hingga 4 minggu setelah tanggal ujian.',
            ],
            [
                'category' => 'Lainnya',
                'question' => 'Saya lupa kata sandi akun saya, bagaimana cara reset?',
                'answer' => 'Gunakan fitur lupa password di halaman login atau hubungi admin resmi pada jam kerja jika membutuhkan bantuan lebih lanjut.',
            ],
        ];
    }
}
