<?php

namespace Database\Seeders;

use App\Models\SatkerModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satkers =[
            [
                "provinsi_id" => 1,
                "kode_satker" => "006067",
                "address" => "Jl. Cut Mutia No.18, Kp. Baru, Kec. Baiturrahman, Kota Banda Aceh, Aceh 23116",
                "name" => "Kejaksaan Negeri Banda Aceh"
            ],[
                "provinsi_id" => 2,
                "kode_satker" => "006320",
                "address" => "Jl. Yos Sudarso, Lalang, Kec. Rambutan, Kota Tebing Tinggi, Sumatera Utara 20998",
                "name" => "Kejaksaan Negeri Tebing Tinggi"
            ],[
                "provinsi_id" => 3,
                "kode_satker" => "006639",
                "address" => "Jl. Gajah Mada No.22, Kp. Olo, Kec. Nanggalo, Kota Padang, Sumatera Barat 25173",
                "name" => "Kejaksaan Negeri Padang"
            ],[
                "provinsi_id" => 3,
                "kode_satker" => "006753",
                "address" => "Jl. Soekarno - Hatta No.175, Bulakan Balai Kandih, Kec. Payakumbuh Bar., Kota Payakumbuh, Sumatera Barat 26223",
                "name" => "Kejaksaan Negeri Payakumbuh"
            ],[
                "provinsi_id" => 4,
                "kode_satker" => "006821",
                "address" => "Jl. Jend. Sudirman No.295, Simpang Empat, Kec. Pekanbaru Kota, Kota Pekanbaru, Riau 28121",
                "name" => "Kejaksaan Negeri Pekanbaru"
            ],[
                "provinsi_id" => 5,
                "kode_satker" => "007009",
                "address" => "Jl. A Yani No.14, Telanaipura, Kec. Telanaipura, Kota Jambi, Jambi 36361",
                "name" => "Kejaksaan Negeri Jambi"
            ],[
                "provinsi_id" => 6,
                "kode_satker" => "007119",
                "address" => "Jl. Gub H Bastari No.502, 8 Ulu, Kecamatan Seberang Ulu I, Kota Palembang, Sumatera Selatan 30257",
                "name" => "Kejaksaan Negeri Palembang"
            ],[
                "provinsi_id" => 7,
                "kode_satker" => "009603",
                "address" => "Menggala Sel., Kec. Menggala, Kab. Tulang Bawang, Lampung 34388",
                "name" => "Kejaksaan Negeri Tulang Bawang"
            ],[
                "provinsi_id" => 8,
                "kode_satker" => "005020",
                "address" => "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950",
                "name" => "Kejaksaan Tinggi DKI Jakarta"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005236",
                "address" => "Jalan Insinyur Haji Juanda No.6, RT.03/RW.01, Pabaton, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16121",
                "name" => "Kejaksaan Negeri Kota Bogor"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005193",
                "address" => "l. Siliwangi No.25, Nagri Kidul, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 40154",
                "name" => "Kejaksaan Negeri Purwakarta"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005222",
                "address" => "Jl. Veteran No.1, RT.002/RW.002, Marga Jaya, Kec. Bekasi Sel., Kota Bks, Jawa Barat 17141",
                "name" => "Kejaksaan Negeri Kota Bekasi"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005105",
                "address" => "Jl. Jaksa Naranata No.11, Baleendah, Kec. Baleendah, Kabupaten Bandung, Jawa Barat 40375",
                "name" => "Kejaksaan Negeri Kabupaten Bandung"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005112",
                "address" => "Jl. Pangeran Soeriaatmadja No.2, Kotakulon, Kec. Sumedang Sel., Kabupaten Sumedang, Jawa Barat 45311",
                "name" => "Kejaksaan Negeri Sumedang"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "005201",
                "address" => "Jl. Jaksa Agung R. Suprato No. 4 Karangpawitan, Jl. Singaperbangsa, Nagasari, Kec. Karawang Bar., Karawang, Jawa Barat 41312",
                "name" => "Kejaksaan Negeri Karawang"
            ],[
                "provinsi_id" => 9,
                "kode_satker" => "650388",
                "address" => "Komplek Perkantoran Pemerintah Daerah, Sukamahi, Kec. Cikarang Pusat, Kabupaten Bekasi, Jawa Barat 17530",
                "name" => "Kejaksaan Negeri Kabupaten Bekasi"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005531",
                "address" => "Jl. Kepatihan No.1, Kepatihan Wetan, Kec. Jebres, Kota Surakarta, Jawa Tengah 57129",
                "name" => "Kejaksaan Negeri Surakarta"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005498",
                "address" => "Jl. Veteran No.9, Magelang, Kec. Magelang Tengah, Kota Magelang, Jawa Tengah 56117",
                "name" => "Kejaksaan Negeri Kota Magelang"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005332",
                "address" => "Jl. Jenderal Sudirman, Pati Kidul, Ngarus, Kec. Pati, Kabupaten Pati, Jawa Tengah 59112",
                "name" => "Kejaksaan Negeri Pati"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005421",
                "address" => "Jl. Jend. Sudirman, Gendongan, Kec. Tingkir, Kota Salatiga, Jawa Tengah 50743",
                "name" => "Kejaksaan Negeri Salatiga"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005510",
                "address" => "Jl. Pahlawan No.134, Kebumen, Kec. Kebumen, Kabupaten Kebumen, Jawa Tengah 54311",
                "name" => "Kejaksaan Negeri Kebumen"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005367",
                "address" => "Jl. Raya Soekarno-Hatta No.189, Patukangan, Kec. Kendal, Kabupaten Kendal, Jawa Tengah 51313",
                "name" => "Kejaksaan Negeri Kendal"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005477",
                "address" => "Blora, Tempelan, Kec. Blora, Kabupaten Blora, Jawa Tengah 58219",
                "name" => "Kejaksaan Negeri Blora"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005456",
                "address" => "Jl. Kh. Ahmad Fauzan No.3, Pengkol VII, Pengkol, Kec. Jepara, Kabupaten Jepara, Jawa Tengah 59415",
                "name" => "Kejaksaan Negeri Jepara"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005566",
                "address" => "Jl. Lawu No.361, Badran Asri, Cangakan, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57712",
                "name" => "Kejaksaan Negeri Karanganyar"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005634",
                "address" => "Jl. Ajibarang Secang No.285, Banyumas, Sudagaran, Kec. Banyumas, Kabupaten Banyumas, Jawa Tengah 53192",
                "name" => "Kejaksaan Negeri Banyumas"
            ],[
                "provinsi_id" => 10,
                "kode_satker" => "005371",
                "address" => "Jl. Jendral Sudirman No.413, RW.03, Kasepuhan, Kec. Batang, Kabupaten Batang, Jawa Tengah 51216",
                "name" => "Kejaksaan Negeri Batang"
            ],[
                "provinsi_id" => 11,
                "kode_satker" => "005680",
                "address" => "Jl. Parasamya No.6, Beran Lor, Tridadi, Kec. Sleman, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55511",
                "name" => "Kejaksaan Negeri Sleman"
            ],[
                "provinsi_id" => 11,
                "kode_satker" => "005697",
                "address" => "Jl. Sugiman No.16, Kemiri, Wates, Kec. Pengasih, Kabupaten Kulon Progo, Daerah Istimewa Yogyakarta 55651",
                "name" => "Kejaksaan Negeri Kulon Progo"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005723",
                "address" => "Jl. Raya Sukomanunggal Jaya No.1, Sukomanunggal, Kec. Sukomanunggal, Surabaya, Jawa Timur 60188",
                "name" => "Kejaksaan Negeri Surabaya"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005730",
                "address" => "Jl. Rajekwesi No.31, Jetak, Kec. Bojonegoro, Kabupaten Bojonegoro, Jawa Timur 62114",
                "name" => "Kejaksaan Negeri Bojonegoro"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005790",
                "address" => "Jalan Sultan Agung No. 36 Sidokumpul, Gajah Timur, Magersari, Kabupaten Sidoarjo, Jawa Timur 61212",
                "name" => "Kejaksaan Negeri Sidoarjo"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005840",
                "address" => "Jl. Jaksa Agung Suprapto No.63, Penganjuran, Kec. Banyuwangi, Kabupaten Banyuwangi, Jawa Timur 68416",
                "name" => "Kejaksaan Negeri Banyuwangi"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005744",
                "address" => "JL. Raya Permata No. 2, Kembangan, 61161 Gresik, Indonesia, Kembangan, Kebomas, Gresik Regency, East Java 61124",
                "name" => "Kejaksaan Negeri Gresik"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005971",
                "address" => "Jl. Karya Dharma No.177, Jawar, Ringinagung, Kec. Magetan, Kabupaten Magetan, Jawa Timur 63319",
                "name" => "Kejaksaan Negeri Magetan"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005772",
                "address" => "Jl. RA. Kartini, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311",
                "name" => "Kejaksaan Negeri Tuban"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "005765",
                "address" => "Jl. Veteran No.4, Dapur Timur, Banjarmendalan, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur 62212",
                "name" => "Kejaksaan Negeri Lamongan"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "006046",
                "address" => "Jl. Raya Panglima Sudirman No.41, Patokan, Kec. Kraksaan, Kabupaten Probolinggo, Jawa Timur 67282",
                "name" => "Kejaksaan Negeri Kabupaten Probolinggo"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "680001",
                "address" => "Jl. Raya Surabaya - Madiun No.KM.09, Satriyan, Gading, Kec. Balerejo, Kabupaten Madiun, Jawa Timur 63152",
                "name" => "Kejaksaan Negeri Kabupaten Madiun"
            ],[
                "provinsi_id" => 12,
                "kode_satker" => "055235",
                "address" => "Jl. Raya By Pass, Mergelo, Meri, Kec. Magersari, Kota Mojokerto, Jawa Timur 61315",
                "name" => "Kejaksaan Negeri Kota Mojokerto"
            ],[
                "provinsi_id" => 13,
                "kode_satker" => "007392",
                "address" => "Melayu, Kec. Singkawang Bar., Kota Singkawang, Kalimantan Barat 79111",
                "name" => "Kejaksaan Negeri Singkawang"
            ],[
                "provinsi_id" => 13,
                "kode_satker" => "007411",
                "address" => "Jl. Irian No. 44 Kelurahan Tanjung Sekayam Kecamatan Kapuas, Sungai Sengkuang, Sanggau, Kabupaten Sanggau, Kalimantan Barat 78516",
                "name" => "Kejaksaan Negering Sanggau"
            ],[
                "provinsi_id" => 14,
                "kode_satker" => "007610",
                "address" => "Tibung Raya, Kec. Kandangan, Kabupaten Hulu Sungai Selatan, Kalimantan Selatan 71213",
                "name" => "Kejaksaan Negeri Hulu Sungai Selatan"
            ],[
                "provinsi_id" => 14,
                "kode_satker" => "007680",
                "address" => "JL. Jaksa Agung Suprapto, Tanjung, Jangkung, Kec. Tj., Kabupaten Tabalong, Kalimantan Selatan 71513",
                "name" => "Kejaksaan Negeri Tabalong"
            ],[
                "provinsi_id" => 14,
                "kode_satker" => "650367",
                "address" => "Jl. Trikora No.2, Guntung Paikat, Kec. Banjarbaru Selatan, Kota Banjar Baru, Kalimantan Selatan 70714",
                "name" => "Kejaksaan Negeri Banjarbaru"
            ],[
                "provinsi_id" => 15,
                "kode_satker" => "007737",
                "address" => "Jl. M. Yamin No.4, Gn. Kelua, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur 75123",
                "name" => "Kejaksaan Negeri Samarinda"
            ],[
                "provinsi_id" => 16,
                "kode_satker" => "007847",
                "address" => "Matani III, Tomohon Tengah, Talete Dua, Kec. Tomohon Tengah, Kota Tomohon, Sulawesi Utara",
                "name" => "Kejaksaan Negeri Tomohon"
            ],[
                "provinsi_id" => 17,
                "kode_satker" => "007889",
                "address" => "l. Prof. Moh. Yamin No.97, Tatura Utara, Kec. Palu Sel., Kota Palu, Sulawesi Tengah 94105",
                "name" => "Kejaksaan Negeri Palu"
            ],[
                "provinsi_id" => 18,
                "kode_satker" => "200637",
                "address" => "Jl. Adiyaksa, Lampopala, Kec. Rumbia, Kabupaten Bombana, Sulawesi Tenggara 93771",
                "name" => "Kejaksaan Negeri Bombana"
            ],[
                "provinsi_id" => 19,
                "kode_satker" => "008242",
                "address" => "Jl. Batara, Boting, Kec. Wara, Kota Palopo, Sulawesi Selatan 91911",
                "name" => "Kejaksaan Negeri Palopo"
            ],[
                "provinsi_id" => 19,
                "kode_satker" => "008128",
                "address" => "Jl. Sultan Hasanuddin No.43, Coppo, Kec. Barru, Kabupaten Barru, Sulawesi Selatan 90712",
                "name" => "Kejaksaan Negeri Barru"
            ],[
                "provinsi_id" => 19,
                "kode_satker" => "008369",
                "address" => "Jl. WR. Supratman No.1, Benteng, Kec. Benteng, Kab. Kepulauan Selayar, Sulawesi Selatan 92812",
                "name" => "Kejaksaan Negeri Kepulauan Selayar"
            ],[
                "provinsi_id" => 20,
                "kode_satker" => "008682",
                "address" => "Jl. Jend. Sudirman No.3, Dauh Puri, Kec. Denpasar Bar., Kota Denpasar, Bali 80113",
                "name" => "Kejaksaan Negeri Denpasar"
            ],[
                "provinsi_id" => 20,
                "kode_satker" => "008711",
                "address" => "Jl. Kapten Jaya Tirta No.1, Karangasem, Kec. Karangasem, Kabupaten Karangasem, Bali 80811",
                "name" => "Kejaksaan Negeri Karangasem"
            ],[
                "provinsi_id" => 20,
                "kode_satker" => "417648",
                "address" => "Mengwitani, Kec. Mengwi, Kabupaten Badung, Bali 80351",
                "name" => "Kejaksaan Negeri Badung"
            ],[
                "provinsi_id" => 21,
                "kode_satker" => "417842",
                "address" => "Jl. Pandeglang - Rangkas Bitung No.17, Pandeglang, Kec. Pandeglang, Kabupaten Pandeglang, Banten 42211",
                "name" => "Kejaksaan Negeri Pandeglang"
            ],[
                "provinsi_id" => 21,
                "kode_satker" => "417645",
                "address" => "Jl. Promoter No.2, Lengkong Gudang Tim., Kec. Serpong, Kota Tangerang Selatan, Banten 15310",
                "name" => "Kejaksaan Negeri Tangerang Selatan"
            ],[
                "provinsi_id" => 22,
                "kode_satker" => "664408",
                "address" => "Jl. Jaksa Agung Suprapto No.1, Ulanta, Kec. Suwawa, Kabupaten Bone Bolango, Gorontalo 96121",
                "name" => "Kejaksaan Negeri Bone Bolango"
            ],[
                "provinsi_id" => 23,
                "kode_satker" => "006973",
                "address" => "Jl. Engku Putri No.1, Tlk. Tering, Kec. Batam Kota, Kota Batam, Kepulauan Riau 29432",
                "name" => "Kejaksaan Negeri Batam"
            ]
        ];

        foreach($satkers  as $satker)
          {
             $inputs[] = [
                'provinsi_id' => $satker['provinsi_id'],
                'name'        => $satker['name'],
                'address'     => $satker['address'],
                'kode_satker' => $satker['kode_satker']
            ];
          }
         SatkerModel::insert($inputs);
    }
}
