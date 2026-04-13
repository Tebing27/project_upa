<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProfilPage extends Component
{
    public string $activeTab = 'visi';

    /** @var array<int, array{name: string, title: string, prefix: string, image: string}> */
    public array $staff = [
        [
            'name' => 'Dr. Kusumajanti, S.Sos., M.M., M.Si.',
            'title' => 'Kepala UPA LUK',
            'prefix' => '',
            'image' => '/assets/Dr.Kusumajanti.webp',
        ],
        [
            'name' => 'Furqaan Fathin Waliyuddin, SH.',
            'title' => 'Staf UPA LUK',
            'prefix' => 'Prefix',
            'image' => '/assets/FurqaanFathinWaliyuddin.webp',
        ],
        [
            'name' => 'Yami',
            'title' => 'Staf UPA LUK',
            'prefix' => 'Prefix',
            'image' => '/assets/yami.webp',
        ],
    ];

    /**
     * @var array<string, mixed>
     */
    public array $profileContent = [];

    public function mount(): void
    {
        $this->profileContent = $this->loadProfileContent();
        $this->staff = $this->buildStaffMembers();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.profil-page', [
            'profileHeading' => $this->profileContent['profil_heading'] ?? 'Unit Penunjang Akademik - Lembaga Uji Kompetensi',
            'profileText' => $this->profileContent['profil_text'] ?? 'Lembaga Sertifikasi Profesi (LSP) adalah lembaga pelaksanaan kegiatan sertifikasi profesi yang memperoleh lisensi dari Badan Nasional Sertifikasi Profesi (BNSP). Lisensi diberikan melalui proses akreditasi oleh BNSP yang menyatakan bahwa LSP bersangkutan telah memenuhi syarat untuk melakukan kegiatan sertifikasi profesi. Sebagai organisasi tingkat nasional yang berkedudukan di wilayah Republik Indonesia, LSP dapat membuka cabang yang berkedudukan di kota lain / suatu tempat.',
            'tabContent' => $this->buildTabContent(),
            'structureHeading' => $this->profileContent['structure_heading'] ?? 'Bagan Susunan Pengurus',
            'structureImage' => $this->profileContent['structure_image'] ?? asset('images/struktur-organisasi-luk.jpeg'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadProfileContent(): array
    {
        $page = Page::query()
            ->where('slug', 'profil')
            ->with(['pageSections.fields.value.mediaFile'])
            ->first();

        if (! $page) {
            return [];
        }

        $content = [];

        foreach ($page->pageSections as $section) {
            foreach ($section->fields as $field) {
                $content[$field->field_key] = $field->isImage()
                    ? ($field->value?->imageUrl() ?? null)
                    : ($field->value?->value ?? null);
            }
        }

        return $content;
    }

    /**
     * @return array<string, array{title: string, quote: string|null, items: array<int, string>}>
     */
    private function buildTabContent(): array
    {
        return [
            'visi' => [
                'title' => $this->profileContent['visi_title'] ?? 'Visi UPA LUK - UPNVJ',
                'quote' => $this->profileContent['visi_text'] ?? 'Menjadi lembaga sertifikasi profesional yang mandiri dan terpercaya dengan identitas bela negara.',
                'items' => [],
            ],
            'misi' => [
                'title' => $this->profileContent['misi_title'] ?? 'Misi UPA LUK - UPNVJ',
                'quote' => null,
                'items' => $this->normalizeList($this->profileContent['misi_items'] ?? "Menyelenggarakan sertifikasi sumber daya manusia secara profesional.\nMengembangkan skema sertifikasi kompetensi program studi yang terpercaya dan identitas bela negara.\nMeningkatkan pengakuan dan daya saing lulusan yang beridentitas bela negara baik di tingkat nasional maupun internasional.\nMembangun kerja sama yang sinergis dengan para pemangku kepentingan."),
            ],
            'tugas' => [
                'title' => $this->profileContent['tugas_title'] ?? 'Tugas',
                'quote' => null,
                'items' => $this->normalizeList($this->profileContent['tugas_items'] ?? "Menyusun materi uji kompetensi.\nMenyediakan asesor uji kompetensi.\nMelaksanakan asesmen.\nMengembangkan kualifikasi dengan mengacu pada KKNI.\nMemelihara kinerja asesor dan TUK.\nPengembangan skema sertifikasi."),
            ],
            'wewenang' => [
                'title' => $this->profileContent['wewenang_title'] ?? 'Wewenang',
                'quote' => null,
                'items' => $this->normalizeList($this->profileContent['wewenang_items'] ?? "Menetapkan biaya sertifikasi kompetensi.\nMenerbitkan sertifikat kompetensi.\nMencabut atau membatalkan sertifikasi kompetensi.\nMendirikan dan memverifikasi TUK.\nMemberikan sanksi kepada asesor dan TUK jika melanggar aturan.\nMengusulkan standar kompetensi baru."),
            ],
        ];
    }

    /**
     * @return array<int, array{name: string, title: string, prefix: string, image: string}>
     */
    private function buildStaffMembers(): array
    {
        $members = [
            [
                'name' => $this->profileContent['leader_name'] ?? 'Dr. Kusumajanti, S.Sos., M.M., M.Si.',
                'title' => $this->profileContent['leader_title'] ?? 'Kepala UPA LUK UPN Veteran Jakarta',
                'prefix' => '',
                'image' => $this->profileContent['leader_image'] ?? asset('assets/Dr.Kusumajanti.webp'),
            ],
            [
                'name' => $this->profileContent['staff_1_name'] ?? 'Furqaan Fathin Waliyuddin, SH.',
                'title' => $this->profileContent['staff_1_title'] ?? 'Staf UPA LUK',
                'prefix' => $this->profileContent['staff_1_prefix'] ?? '',
                'image' => $this->profileContent['staff_1_image'] ?? asset('assets/FurqaanFathinWaliyuddin.webp'),
            ],
            [
                'name' => $this->profileContent['staff_2_name'] ?? 'Yami',
                'title' => $this->profileContent['staff_2_title'] ?? 'Staf UPA LUK',
                'prefix' => $this->profileContent['staff_2_prefix'] ?? '',
                'image' => $this->profileContent['staff_2_image'] ?? asset('assets/yami.webp'),
            ],
        ];

        return array_values(array_filter($members, function (array $member): bool {
            return filled($member['name']) && filled($member['title']);
        }));
    }

    /**
     * @return array<int, string>
     */
    private function normalizeList(?string $value): array
    {
        return Collection::make(preg_split('/\R/u', (string) $value) ?: [])
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
