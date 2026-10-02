<?php

namespace App\Services\AI;

/**
 * Referensi mini terkurasi untuk istilah kesehatan dasar yang sering ditanyakan.
 * Bukan diagnosis. Tujuannya mencegah model kecil mengarang fakta anatomi sederhana.
 */
class MedicalReferenceService
{
    public function __construct(private readonly HealthQueryAnalyzer $analyzer)
    {
    }

    /** @return array{title:string,definition:string,points:array<int,string>,note:?string}|null */
    public function definitionFor(string $question): ?array
    {
        $key = $this->analyzer->topicKey($question);

        $references = [
            'gigi' => $this->ref('Gigi', 'Gigi adalah struktur keras di dalam rongga mulut yang membantu menggigit dan mengunyah makanan.', [
                'Membantu memotong, merobek, dan menghaluskan makanan.',
                'Juga membantu pengucapan beberapa bunyi dan menopang bentuk rahang.',
            ], 'Nyeri gigi, gusi bengkak, atau gigi berlubang sebaiknya diperiksa oleh tenaga kesehatan gigi.'),
            'mulut' => $this->ref('Mulut', 'Mulut adalah bagian awal saluran pencernaan yang mencakup bibir, gigi, gusi, lidah, dan jaringan di rongga mulut.', [
                'Berperan dalam makan, mengunyah, menelan, berbicara, dan membantu pengecapan.',
                'Air liur membantu membasahi makanan dan memulai proses pencernaan.',
            ], 'Sariawan yang tidak membaik, perdarahan, benjolan, atau nyeri menetap perlu diperiksa.'),
            'bibir' => $this->ref('Bibir', 'Bibir adalah jaringan lunak yang membatasi bagian depan rongga mulut.', [
                'Membantu berbicara, makan, minum, dan ekspresi wajah.',
                'Kulit bibir lebih tipis sehingga mudah kering atau iritasi.',
            ], null),
            'lidah' => $this->ref('Lidah', 'Lidah adalah organ berotot di dalam mulut yang membantu pengecapan, mengunyah, menelan, dan berbicara.', [
                'Permukaannya memiliki papila yang berperan dalam pengecapan.',
                'Gerakan lidah membantu mendorong makanan saat proses menelan.',
            ], null),
            'rahang' => $this->ref('Rahang', 'Rahang adalah struktur tulang yang menopang gigi dan membantu gerakan mengunyah serta berbicara.', [
                'Rahang atas relatif tetap, sedangkan rahang bawah bergerak melalui sendi rahang.',
                'Keluhan nyeri atau bunyi pada sendi rahang dapat memiliki berbagai penyebab.',
            ], null),
            'perut' => $this->ref('Perut', 'Dalam bahasa sehari-hari, perut biasanya merujuk pada area abdomen di antara dada dan panggul, bukan satu organ tertentu.', [
                'Di area perut terdapat lambung, usus, hati, pankreas, ginjal, dan organ lainnya.',
                'Karena banyak organ berada di area ini, lokasi dan sifat nyeri penting untuk membantu menilai keluhan.',
            ], null),
            'lambung' => $this->ref('Lambung', 'Lambung adalah organ pencernaan berbentuk kantong yang menerima makanan dari kerongkongan sebelum diteruskan ke usus halus.', [
                'Mengaduk makanan dan mencampurnya dengan asam serta enzim pencernaan.',
                'Membantu memecah makanan sebelum proses pencernaan dilanjutkan di usus.',
            ], 'Keluhan perih atau panas di ulu hati dapat memiliki beberapa penyebab dan tidak selalu berarti satu penyakit tertentu.'),
            'usus' => $this->ref('Usus', 'Usus adalah bagian panjang dari saluran pencernaan yang terdiri dari usus halus dan usus besar.', [
                'Usus halus terutama menyerap zat gizi dari makanan.',
                'Usus besar menyerap air dan membantu pembentukan tinja.',
            ], null),
            'jantung' => $this->ref('Jantung', 'Jantung adalah organ berotot yang memompa darah ke seluruh tubuh.', [
                'Mengalirkan darah beroksigen ke jaringan tubuh.',
                'Menjaga sirkulasi agar organ mendapat oksigen dan nutrisi.',
            ], 'Nyeri dada berat, sesak berat, pingsan, atau keringat dingin mendadak perlu dinilai segera secara langsung.'),
            'paru' => $this->ref('Paru-paru', 'Paru-paru adalah organ utama sistem pernapasan yang melakukan pertukaran oksigen dan karbon dioksida.', [
                'Mengambil oksigen dari udara untuk masuk ke darah.',
                'Membuang karbon dioksida saat kita mengembuskan napas.',
            ], null),
            'hati' => $this->ref('Hati', 'Hati atau liver adalah organ besar di bagian kanan atas perut yang menjalankan banyak fungsi metabolisme tubuh.', [
                'Membantu mengolah zat gizi dan menyimpan energi.',
                'Menghasilkan empedu dan membantu memproses berbagai zat di dalam darah.',
            ], null),
            'ginjal' => $this->ref('Ginjal', 'Ginjal adalah sepasang organ yang menyaring darah dan membantu membentuk urine.', [
                'Membuang sisa metabolisme dan kelebihan cairan melalui urine.',
                'Membantu menjaga keseimbangan cairan, elektrolit, dan tekanan darah.',
            ], null),
            'otak' => $this->ref('Otak', 'Otak adalah pusat sistem saraf yang mengatur pikiran, gerakan, sensasi, emosi, dan banyak fungsi tubuh.', [
                'Menerima dan memproses informasi dari tubuh dan lingkungan.',
                'Mengatur berbagai fungsi sadar maupun otomatis bersama sistem saraf.',
            ], null),
            'kulit' => $this->ref('Kulit', 'Kulit adalah organ terluar tubuh yang melindungi jaringan di bawahnya dari lingkungan.', [
                'Membantu melindungi tubuh dari cedera, kuman, dan kehilangan cairan.',
                'Berperan dalam sensasi serta pengaturan suhu tubuh.',
            ], null),
            'mata' => $this->ref('Mata', 'Mata adalah organ penglihatan yang menangkap cahaya dan mengubahnya menjadi sinyal yang diproses oleh otak.', [
                'Kornea dan lensa membantu memfokuskan cahaya.',
                'Retina mengubah cahaya menjadi sinyal saraf.',
            ], null),
            'telinga' => $this->ref('Telinga', 'Telinga adalah organ yang berperan dalam pendengaran dan membantu keseimbangan tubuh.', [
                'Telinga luar dan tengah membantu menghantarkan suara.',
                'Telinga dalam mengubah getaran menjadi sinyal saraf dan ikut mengatur keseimbangan.',
            ], null),
            'hidung' => $this->ref('Hidung', 'Hidung adalah bagian utama saluran napas atas dan juga berperan dalam penciuman.', [
                'Membantu menyaring, menghangatkan, dan melembapkan udara yang masuk.',
                'Reseptor penciuman membantu mengenali bau.',
            ], null),
            'tenggorokan' => $this->ref('Tenggorokan', 'Tenggorokan adalah area yang menghubungkan rongga hidung dan mulut dengan saluran napas serta saluran pencernaan.', [
                'Berperan dalam proses menelan dan jalur udara menuju saluran napas.',
                'Nyeri tenggorokan dapat terjadi karena berbagai penyebab, termasuk iritasi dan infeksi.',
            ], null),
            'tangan' => $this->ref('Tangan', 'Tangan adalah bagian anggota gerak atas yang terdiri dari telapak, jari, tulang, sendi, otot, tendon, saraf, dan pembuluh darah.', [
                'Digunakan untuk menggenggam, meraba, menulis, dan berbagai gerakan halus.',
                'Gerak tangan bergantung pada kerja bersama tulang, sendi, otot, tendon, dan saraf.',
            ], null),
            'kaki' => $this->ref('Kaki', 'Kaki adalah bagian anggota gerak bawah yang membantu menopang tubuh, berdiri, berjalan, berlari, dan menjaga keseimbangan.', [
                'Terdiri dari tulang, sendi, otot, tendon, ligamen, saraf, dan pembuluh darah.',
                'Keluhan kaki perlu dinilai berdasarkan lokasi tepat, riwayat cedera, bengkak, kemerahan, dan kemampuan menapak.',
            ], null),
            'lutut' => $this->ref('Lutut', 'Lutut adalah sendi besar yang menghubungkan paha dan tungkai bawah.', [
                'Membantu menekuk dan meluruskan tungkai saat berdiri, berjalan, atau naik tangga.',
                'Stabilitas lutut dibantu oleh ligamen, meniskus, otot, dan tendon.',
            ], null),
            'tulang' => $this->ref('Tulang', 'Tulang adalah jaringan keras yang membentuk kerangka tubuh.', [
                'Menopang tubuh dan melindungi organ.',
                'Bersama sendi dan otot, tulang memungkinkan tubuh bergerak.',
            ], null),
            'otot' => $this->ref('Otot', 'Otot adalah jaringan tubuh yang dapat berkontraksi untuk menghasilkan gerakan.', [
                'Otot rangka membantu gerakan tubuh dan menjaga postur.',
                'Jenis otot lain juga bekerja pada organ dalam dan jantung.',
            ], null),
            'sendi' => $this->ref('Sendi', 'Sendi adalah tempat pertemuan dua atau lebih tulang yang memungkinkan gerakan dan memberi stabilitas.', [
                'Beberapa sendi bergerak luas, sedangkan yang lain bergerak sangat sedikit.',
                'Nyeri sendi dapat disebabkan berbagai hal dan perlu dinilai dari lokasi, bengkak, kekakuan, serta riwayat cedera.',
            ], null),
            'darah' => $this->ref('Darah', 'Darah adalah jaringan cair yang beredar melalui pembuluh darah di seluruh tubuh.', [
                'Membawa oksigen, zat gizi, hormon, dan berbagai zat penting.',
                'Juga membantu pertahanan tubuh, pembekuan, dan pembuangan sisa metabolisme.',
            ], null),
            'pankreas' => $this->ref('Pankreas', 'Pankreas adalah organ di bagian atas perut yang berperan dalam pencernaan dan pengaturan gula darah.', [
                'Menghasilkan enzim untuk membantu mencerna makanan.',
                'Menghasilkan hormon seperti insulin dan glukagon.',
            ], null),
            'rahim' => $this->ref('Rahim', 'Rahim atau uterus adalah organ reproduksi perempuan yang berada di panggul.', [
                'Menjadi tempat tumbuh dan berkembangnya janin selama kehamilan.',
                'Lapisan dalam rahim mengalami perubahan selama siklus menstruasi.',
            ], null),
            'uterus' => $this->ref('Rahim', 'Rahim atau uterus adalah organ reproduksi perempuan yang berada di panggul.', [
                'Menjadi tempat tumbuh dan berkembangnya janin selama kehamilan.',
                'Lapisan dalam rahim mengalami perubahan selama siklus menstruasi.',
            ], null),
            'spirometri' => $this->ref('Spirometri', 'Spirometri adalah pemeriksaan fungsi paru yang mengukur jumlah dan kecepatan udara saat seseorang menarik dan mengembuskan napas.', [
                'Dapat membantu menilai pola gangguan aliran udara pada paru.',
                'Hasilnya perlu ditafsirkan bersama kondisi dan pemeriksaan klinis pasien.',
            ], null),
            'demam' => $this->ref('Demam', 'Demam adalah kenaikan suhu tubuh di atas kisaran normal, biasanya sebagai respons tubuh terhadap suatu kondisi seperti infeksi atau peradangan.', [
                'Demam adalah gejala, bukan satu penyakit tertentu.',
                'Penyebabnya perlu dinilai bersama gejala lain dan lama keluhan.',
            ], null),
            'batuk' => $this->ref('Batuk', 'Batuk adalah refleks tubuh untuk membantu membersihkan saluran napas dari lendir, iritan, atau benda asing.', [
                'Batuk dapat terjadi pada banyak kondisi sehingga penyebabnya tidak bisa ditentukan dari satu gejala saja.',
                'Lama batuk dan gejala penyerta seperti demam atau sesak membantu penilaian awal.',
            ], null),
            'diare' => $this->ref('Diare', 'Diare adalah buang air besar yang lebih encer dan biasanya lebih sering daripada kebiasaan seseorang.', [
                'Hal penting yang perlu dijaga adalah kecukupan cairan untuk mencegah dehidrasi.',
                'Darah pada tinja, lemas berat, atau tanda dehidrasi perlu mendapat perhatian medis.',
            ], null),
            'hipertensi' => $this->ref('Hipertensi', 'Hipertensi adalah kondisi ketika tekanan darah menetap lebih tinggi dari batas yang dianggap sehat.', [
                'Sering kali tidak menimbulkan gejala sehingga pengukuran tekanan darah penting.',
                'Penilaian tidak cukup hanya dari satu kali pengukuran pada semua situasi.',
            ], null),
            'diabetes' => $this->ref('Diabetes', 'Diabetes mellitus adalah kondisi ketika kadar gula darah terlalu tinggi karena gangguan produksi atau kerja insulin.', [
                'Diagnosis memerlukan pemeriksaan medis dan pemeriksaan gula darah yang sesuai.',
                'Pengelolaan dapat melibatkan pola makan, aktivitas fisik, pemantauan, dan obat sesuai penilaian tenaga medis.',
            ], null),
            'anemia' => $this->ref('Anemia', 'Anemia adalah kondisi ketika jumlah sel darah merah atau kadar hemoglobin tidak mencukupi untuk membawa oksigen secara optimal.', [
                'Keluhan dapat berupa lemas, mudah lelah, pucat, atau berdebar, tetapi gejala tersebut tidak spesifik.',
                'Penyebab anemia beragam dan biasanya perlu dinilai dengan pemeriksaan darah.',
            ], null),
            'asma' => $this->ref('Asma', 'Asma adalah penyakit saluran napas kronis yang dapat menyebabkan penyempitan saluran napas secara berubah-ubah.', [
                'Keluhan dapat berupa mengi, sesak, batuk, atau rasa berat di dada.',
                'Diagnosis dan rencana terapi perlu ditentukan melalui evaluasi tenaga medis.',
            ], null),
        ];

        return $references[$key] ?? null;
    }

    public function formatDefinition(array $reference): string
    {
        $text = trim((string) $reference['definition']);

        if ($reference['points'] !== []) {
            $text .= "\n\nHal penting:\n";
            foreach ($reference['points'] as $point) {
                $text .= '• '.$point."\n";
            }
            $text = rtrim($text);
        }

        if (! empty($reference['note'])) {
            $text .= "\n\nCatatan:\n".$reference['note'];
        }

        return $text;
    }

    public function symptomContext(string $question): string
    {
        $text = $this->analyzer->normalize($question);

        if (str_contains($text, 'ulu hati') || str_contains($text, 'perut atas') || str_contains($text, 'perut bagian atas')) {
            return $this->ctx('NYERI/PERIH/PANAS PERUT BAGIAN ATAS', [
                'Kemungkinan umum yang boleh disebut secara hati-hati: dispepsia/gangguan lambung, gastritis, refluks asam. Penyebab lain tetap mungkin.',
                'Tanyakan paling penting: lokasi tepat, hubungan dengan makan/telat makan, mual atau muntah.',
                'Tanda bahaya: nyeri sangat berat atau mendadak, muntah darah, BAB hitam, pingsan, nyeri dada atau sesak.',
                'Jangan menyebut kekurangan vitamin/protein sebagai penyebab hanya dari keluhan ini.',
            ]);
        }

        if ($this->hasAny($text, ['perut', 'mules', 'mulas', 'senep', 'weteng'])) {
            return $this->ctx('NYERI/KELUHAN PERUT UMUM', [
                'Nyeri perut dapat berasal dari berbagai organ; jangan menebak penyakit sebelum lokasi, lama keluhan, dan gejala penyerta diketahui.',
                'Tanyakan: lokasi nyeri, sejak kapan, serta ada mual-muntah, diare/sembelit, demam, atau keluhan BAK.',
                'Tanda bahaya: nyeri sangat berat atau mendadak, perut kaku, muntah darah, BAB hitam/berdarah, pingsan, tidak bisa minum, atau kondisi memburuk cepat.',
            ]);
        }

        if ($this->hasAny($text, ['sakit gigi', 'nyeri gigi', 'gigi sakit'])) {
            return $this->ctx('SAKIT GIGI', [
                'Kemungkinan umum yang boleh disebut: karies/gigi berlubang, radang gusi atau jaringan sekitar gigi, sensitivitas gigi.',
                'Tanyakan: dipicu dingin/panas/manis atau sakit terus-menerus, ada bengkak, demam, atau keluar nanah.',
                'Tanda bahaya: bengkak wajah/rahang cepat membesar, sulit menelan/bernapas, demam tinggi dengan kondisi memburuk.',
            ]);
        }

        if (str_contains($text, 'demam')) {
            return $this->ctx('DEMAM', [
                'Demam adalah gejala dan dapat berkaitan dengan banyak penyebab; jangan menentukan penyebab hanya dari suhu tubuh.',
                'Tanyakan: sejak kapan, suhu bila diukur, dan gejala penyerta paling menonjol.',
                'Tanda bahaya: penurunan kesadaran, kejang, sesak berat, lemas berat/dehidrasi, atau kondisi memburuk cepat.',
            ]);
        }

        if (str_contains($text, 'batuk')) {
            return $this->ctx('BATUK', [
                'Batuk dapat berkaitan dengan infeksi saluran napas, iritasi, alergi, atau penyebab lain; jangan memastikan diagnosis dari batuk saja.',
                'Tanyakan: lama batuk, berdahak atau kering, demam, sesak, nyeri dada, atau darah pada dahak.',
                'Tanda bahaya: sesak berat, batuk darah banyak, nyeri dada berat, kebiruan, atau penurunan kesadaran.',
            ]);
        }

        if ($this->hasAny($text, ['diare', 'mencret'])) {
            return $this->ctx('DIARE', [
                'Fokus utama adalah frekuensi BAB, lama keluhan, kemampuan minum, dan tanda dehidrasi.',
                'Tanyakan: sejak kapan, berapa kali, ada darah/lendir, muntah, demam, atau sulit minum.',
                'Tanda bahaya: darah banyak pada tinja, tidak bisa minum, sangat lemas, jarang BAK, pingsan, atau tanda dehidrasi berat.',
            ]);
        }

        if ($this->hasAny($text, ['sakit kepala', 'pusing', 'migrain', 'mumet'])) {
            return $this->ctx('SAKIT KEPALA/PUSING', [
                'Sakit kepala/pusing memiliki banyak penyebab; jangan menyimpulkan diagnosis hanya dari satu gejala.',
                'Tanyakan: sejak kapan, mendadak atau bertahap, lokasi/sifat nyeri, demam, mual, gangguan penglihatan, atau riwayat cedera.',
                'Tanda bahaya: sakit kepala sangat hebat mendadak, kelemahan satu sisi, bicara pelo, kejang, penurunan kesadaran, atau setelah cedera kepala berat.',
            ]);
        }

        if ($this->hasAny($text, ['sesak', 'napas', 'nafas'])) {
            return $this->ctx('SESAK NAPAS', [
                'Sesak napas dapat berkaitan dengan saluran napas, paru, jantung, anemia, kecemasan, atau penyebab lain.',
                'Tanyakan: sejak kapan, muncul saat aktivitas atau istirahat, ada batuk, demam, mengi, atau nyeri dada.',
                'Tanda bahaya: sesak berat saat istirahat, bibir kebiruan, nyeri dada berat, pingsan, atau sulit berbicara karena sesak.',
            ]);
        }

        if ($this->hasAny($text, ['nyeri dada', 'dada sakit', 'sakit dada'])) {
            return $this->ctx('NYERI DADA', [
                'Nyeri dada memiliki banyak kemungkinan dan harus dinilai dari sifat nyeri, pemicu, serta gejala penyerta.',
                'Tanyakan: lokasi, rasa tertekan/tertusuk/panas, dipicu aktivitas atau gerak, dan ada sesak, mual, keringat dingin, atau berdebar.',
                'Tanda bahaya: nyeri dada berat/menekan, menjalar ke lengan/rahang/punggung, sesak, keringat dingin, atau pingsan.',
            ]);
        }

        if ($this->hasAny($text, ['sakit tenggorokan', 'nyeri tenggorokan', 'tenggorokan sakit'])) {
            return $this->ctx('SAKIT TENGGOROKAN', [
                'Sakit tenggorokan dapat berkaitan dengan infeksi, iritasi, alergi, refluks, atau penyebab lain.',
                'Tanyakan: sejak kapan, demam, batuk/pilek, sulit menelan, suara serak, atau adanya bercak/benjolan.',
                'Tanda bahaya: sulit bernapas, sulit menelan air liur, bengkak leher cepat membesar, atau kondisi memburuk cepat.',
            ]);
        }

        if ($this->hasAny($text, ['gatal', 'ruam', 'kulit merah', 'bentol'])) {
            return $this->ctx('GATAL/RUAM KULIT', [
                'Keluhan kulit dapat berkaitan dengan iritasi, alergi, infeksi, gigitan serangga, atau penyebab lain.',
                'Tanyakan: lokasi, sejak kapan, bentuk ruam, menyebar atau tidak, dan ada demam/nyeri/bengkak.',
                'Tanda bahaya: sesak, bengkak bibir/lidah, ruam luas dengan demam tinggi, atau kulit melepuh luas.',
            ]);
        }

        if ($this->hasAny($text, ['kencing sakit', 'nyeri saat kencing', 'anyang anyangan', 'sering kencing', 'urin'])) {
            return $this->ctx('KELUHAN BAK/URINE', [
                'Keluhan saat BAK dapat berkaitan dengan iritasi, infeksi saluran kemih, batu, atau penyebab lain.',
                'Tanyakan: nyeri saat BAK, sering/terdesak, warna urine, demam, nyeri pinggang, dan sejak kapan.',
                'Tanda bahaya: demam tinggi dengan menggigil, nyeri pinggang berat, tidak bisa BAK, atau darah banyak pada urine.',
            ]);
        }

        if ($this->hasAny($text, ['mata merah', 'mata sakit', 'mata gatal', 'penglihatan kabur'])) {
            return $this->ctx('KELUHAN MATA', [
                'Keluhan mata dapat berasal dari iritasi, alergi, infeksi, cedera, atau masalah lain.',
                'Tanyakan: satu atau dua mata, ada belekan, nyeri, silau, trauma, atau penurunan penglihatan.',
                'Tanda bahaya: penurunan penglihatan mendadak, nyeri mata berat, trauma kimia, atau benda asing tajam.',
            ]);
        }

        if ($this->hasAny($text, ['telinga sakit', 'nyeri telinga', 'keluar cairan telinga'])) {
            return $this->ctx('KELUHAN TELINGA', [
                'Nyeri telinga dapat berkaitan dengan infeksi, iritasi saluran telinga, sumbatan, atau masalah di area sekitar.',
                'Tanyakan: sejak kapan, demam, keluar cairan, pendengaran berkurang, atau riwayat kemasukan air/benda.',
                'Tanda bahaya: pusing berat, kelemahan wajah, bengkak belakang telinga, atau kondisi memburuk cepat.',
            ]);
        }

        if ($this->hasAny($text, ['lutut sakit', 'sendi sakit', 'nyeri sendi', 'kaki sakit', 'nyeri kaki', 'bengkak kaki'])) {
            return $this->ctx('NYERI SENDI/KAKI', [
                'Keluhan sendi/kaki dapat berkaitan dengan cedera, penggunaan berlebih, peradangan, gangguan otot/tendon, atau penyebab lain.',
                'Tanyakan: lokasi tepat, ada cedera, bengkak/merah/panas, dan masih bisa menapak atau menggerakkan sendi.',
                'Tanda bahaya: tidak bisa menapak setelah cedera, bentuk anggota gerak berubah, bengkak cepat, mati rasa, atau kaki sangat pucat/dingin.',
            ]);
        }

        return '';
    }

    private function ref(string $title, string $definition, array $points, ?string $note): array
    {
        return compact('title', 'definition', 'points', 'note');
    }

    private function ctx(string $title, array $rules): string
    {
        $text = "REFERENSI SKRINING: {$title}\n";
        foreach ($rules as $rule) {
            $text .= '- '.$rule."\n";
        }
        return rtrim($text);
    }

    private function hasAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }
        return false;
    }
}
