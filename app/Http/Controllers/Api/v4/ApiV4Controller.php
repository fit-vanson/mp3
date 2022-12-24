<?php

namespace App\Http\Controllers\Api\v4;

use App\Http\Controllers\Controller;
use App\Http\Resources\v4\MusicForCategoryResource;
use App\Http\Resources\v4\MusicResource;
use App\Musics;
use Illuminate\Http\Request;

class ApiV4Controller extends Controller
{
    function checkSignSalt($data_info){

        $key="viaviweb";

        $data_json = $data_info;

        $data_arr = json_decode(urldecode(base64_decode($data_json)),true);

        //echo $data_arr['salt'];
        //exit;

        if($data_arr['sign'] == '' && $data_arr['salt'] == '' ){
            //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");

            $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            exit();


        }else{

            $data_arr['salt'];

            $md5_salt=md5($key.$data_arr['salt']);

            if($data_arr['sign']!=$md5_salt){

                //$data['data'] = array("status" => -1, "message" => "Invalid sign salt.");
                $set['ONLINE_MP3_APP'][] = array("status" => -1, "message" => "Invalid sign salt.");
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                exit();
            }
        }

        return $data_arr;

    }
    public function app_details()
    {
        $get_data= $this->checkSignSalt($_POST['data']);
        $site = getSite();
        $app_name = $site->site_app_name;
        $app_logo = \URL::to('/' . $site->site_image);
        $facebook_link = $site->site_direct_link ? $site->site_direct_link : '';

        $twitter_link = $site->twitter_link ? $site->twitter_link : '';
        $instagram_link = $site->instagram_link ? $site->instagram_link : '';
        $youtube_link = $site->youtube_link ? $site->youtube_link : '';
        $google_play_link = $site->site_chplay_link ? $site->site_chplay_link : '';
        $app_package_name = $site->site_package ?? "com.zxcv.onlinemp3";
        $site_ads = json_decode($site->site_ads, true);
        $status_ads = $site->ad_switch;


        $ads[] = [
            "ad_id"=> 5,
            "ads_name"=> "Wortise",
            'ads_info' =>[
                'publisher_id' => "c2f94dbb-e29a-4a95-bbf9-fbf860c428b3",
                'banner_on_off' =>"1",
                'banner_id' =>"acbb8bfe-be23-4252-a173-3bbc0d8dfb8a",
                'interstitial_on_off' => "1",
                'native_on_off' => "1",
                'interstitial_id' => "70f3bdd5-4cd1-4943-a34d-9d23ba25542d",
                'native_id' => "f78f3300-dc1b-4ece-b85b-2bc5ecbca8bb",
                'interstitial_clicks' => 5,
                'native_position' => 5,
            ],
            'status' =>'true',
        ];
        $page_list = [
            [
                'page_id' => 1,
                'page_title' => 'About Us',
                'page_content' => 'About Us',
            ],

            [
                'page_id' => 2,
                'page_title' => 'Terms Of Use',
                'page_content' => 'Terms Of Use',
            ],
            [
                'page_id' => 3,
                'page_title' => 'Privacy Policy',
                'page_content' => $site->site_policy ?? 'ssssss',
            ],

        ];

        $response[] = array(
            'app_package_name' => $app_package_name,
            'app_name' => $app_name,

            "app_email"=>"info@viavilab.com",
            'app_logo' =>  "https://mp3app.viaviweb.in/upload/app_icon.png",
            "app_company"=>  "Viavi Webtech",
            "app_website"=> "www.viaviweb.com",
            "app_contact"=> "+91 9227777522",


            'facebook_link' =>  "https://facebook.com",
            'twitter_link' => "https://twitter.com",
            'instagram_link' => "https://instagram.com",
            'youtube_link' => "https://youtube.com",
            'google_play_link' => "#gp",
            'apple_store_link' => "#ap",
            'app_version' => "1.1.1",
            'app_update_hide_show' => "false",
            'app_update_version_code' => "1.2",
            'app_update_desc' => "Please update new app",
            'app_update_link' =>  "https://google.com",
            'app_update_cancel_option' => "true",
            'song_download' => "true",
            'ads_list' => $ads,
            'page_list' => $page_list,
            'success' => '1');

        return \Response::json(array(
            'ONLINE_MP3_APP' => $response,
            'status_code' => 200
        ));


    }

    public function home(){

//        $get_data= $this->checkSignSalt($_POST['data']);

        $site = getSite();
        $categories = get_categories($site);
        $getMusicCategory = MusicForCategoryResource::collection($categories);

        if(isset($get_data['songs_ids'])){
            $songs_ids= explode(',',$get_data['songs_ids']);
            $musics = Musics::whereIN('id',$songs_ids)->get();
            foreach($musics as $music){

                $getMusic = new MusicResource($music);
            }
        }else{
            $getMusic = [];
        }

//        dd(1);
        $trending_songs = get_songs($site,10,'music_view_count');
        $get_trending_songs = MusicResource::collection($trending_songs);


        $home = '[
      {
        "home_id": 1,
        "home_title": "Popular Category",
        "home_type": "category",
        "home_content": [
          {
            "post_id": "2",
            "post_type": "category",
            "post_title": "Hindi",
            "post_image": "https://mp3app.viaviweb.in/upload/images/category/Hindi_Song.png"
          },
          {
            "post_id": "3",
            "post_type": "category",
            "post_title": "English",
            "post_image": "https://mp3app.viaviweb.in/upload/images/category/English_Song.png"
          },
          {
            "post_id": "4",
            "post_type": "category",
            "post_title": "Punjabi",
            "post_image": "https://mp3app.viaviweb.in/upload/images/category/Punjabi_Song.png"
          },
          {
            "post_id": "5",
            "post_type": "category",
            "post_title": "Gujarati",
            "post_image": "https://mp3app.viaviweb.in/upload/images/category/Gujarati_Song.png"
          }
        ]
      },
      {
        "home_id": 2,
        "home_title": "Weekly Top",
        "home_type": "song",
        "home_content": [
          {
            "song_id": "2",
            "song_title": "Thumkeshwari",
            "song_image": "https://mp3app.viaviweb.in/upload/images/songs/Bhediya.jpg",
            "song_info": "<p>Bhediya &nbsp;by Sachin-Jigar, Rashmeet Kaur, Ash King, Divya Kumar</p>",
            "song_lyrics": "<p>Curvy kamariyaa teri, haay re, meri jaan le gayi, oe</p>\r\n<p>Ban ke bijuriya giri, haay re, pareshaan kar gayi, oe</p>\r\n<p>Curvy kamariyaa teri, haay re, meri jaan le gayi, oe</p>\r\n<p>O-ri, gori, sun ri, sun ri, sun ri</p>",
            "song_type": "server_url",
            "song_url": "http://www.viaviweb.in/envato/cc/demo/mp3/file_example_MP3_5MG.mp3",
            "views": "76",
            "downloads": "3",
            "total_rate": 3,
            "favourite": false,
            "artist_list": [
              {
                "artist_id": "5",
                "artist_name": "Arijit Singh"
              },
              {
                "artist_id": "21",
                "artist_name": "Sonu Nigam"
              }
            ]
          },
          {
            "song_id": "4",
            "song_title": "Kesariya",
            "song_image": "https://mp3app.viaviweb.in/upload/images/songs/Brahmastra.jpg",
            "song_info": "<p>Brahmastra (Original Motion Picture Soundtrack) &nbsp;by Pritam, Arijit Singh, Amitabh Bhattacharya</p>",
            "song_lyrics": "<p>mujko itana bataae koyi<br />kaise tujhse dil naa lagaae koyi?</p>",
            "song_type": "local",
            "song_url": "https://mp3app.viaviweb.in/upload/files/test2.mp3",
            "views": "63",
            "downloads": "5",
            "total_rate": 0,
            "favourite": false,
            "artist_list": [
              {
                "artist_id": "5",
                "artist_name": "Arijit Singh"
              }
            ]
          },
          {
            "song_id": "8",
            "song_title": "Mahi Mera Dil",
            "song_image": "https://mp3app.viaviweb.in/upload/images/songs/Dhokha-Round-D.jpg",
            "song_info": "<p>Dhokha Round D Corner &nbsp;by Arijit Singh, Tulsi Kumar, Tanishk Bagchi, Kumaar</p>",
            "song_lyrics": "<p>beparwah hai maahi mera<br />puchhe naa mera haal ve<br />kisi gal da vi jawaab naa deve<br />मेरे रोते रहे सवाल वे</p>",
            "song_type": "local",
            "song_url": "https://mp3app.viaviweb.in/upload/files/test3.mp3",
            "views": "59",
            "downloads": "6",
            "total_rate": 5,
            "favourite": false,
            "artist_list": [
              {
                "artist_id": "5",
                "artist_name": "Arijit Singh"
              },
              {
                "artist_id": "19",
                "artist_name": "Shreya Ghoshal"
              },
              {
                "artist_id": "14",
                "artist_name": "Kumar Sanu"
              }
            ]
          },
          {
            "song_id": "10",
            "song_title": "Sahi Galat",
            "song_image": "https://mp3app.viaviweb.in/upload/images/songs/Drishyam-2.jpg",
            "song_info": "<p>Drishyam 2 &nbsp;by Amitabh Bhattacharya, King, Devi Sri Prasad</p>",
            "song_lyrics": "<p>तू जहाँ से देखता है, मैं ग़लत हूँ, तू सही<br />देख मेरी नज़रों से, ग़लत, मैं कुछ ग़लत नहीं<br />करना है जो करके ही रहूँगा मैंने तय किया<br />ग़लत को भी सही तरह से करने का निश्चय किया</p>",
            "song_type": "local",
            "song_url": "https://mp3app.viaviweb.in/upload/files/test2.mp3",
            "views": "22",
            "downloads": "4",
            "total_rate": 0,
            "favourite": false,
            "artist_list": [
              {
                "artist_id": "3",
                "artist_name": "Ammy Virk"
              },
              {
                "artist_id": "20",
                "artist_name": "Sidhu Moose Wala"
              }
            ]
          },
          {
            "song_id": "14",
            "song_title": "Manike (From \"Thank God\")",
            "song_image": "https://mp3app.viaviweb.in/upload/images/songs/JhakaasRemakes.jpg",
            "song_info": "<p>Manike (From \"Thank God\") &nbsp;by Yohani, Jubin Nautiyal, Tanishk Bagchi, Surya Ragunaathan</p>",
            "song_lyrics": "<p>haay, ye meri aankhen<br />raat-bhar karen baathen teri, ye teri<br />aaen jo teri yaaden<br />ruk-ruk chalem saansein meri, ye meri</p>",
            "song_type": "local",
            "song_url": "https://mp3app.viaviweb.in/upload/files/test2.mp3",
            "views": "34",
            "downloads": "10",
            "total_rate": 5,
            "favourite": false,
            "artist_list": [
              {
                "artist_id": "16",
                "artist_name": "Miss Pooja"
              },
              {
                "artist_id": "17",
                "artist_name": "Neha Kakkar"
              }
            ]
          }
        ]
      },
      {
        "home_id": 3,
        "home_title": "Best Playlist",
        "home_type": "playlist",
        "home_content": [
          {
            "post_id": "9",
            "post_type": "playlist",
            "post_title": "Best Of Arijit Singh",
            "post_image": "https://mp3app.viaviweb.in/upload/images/playlist/Let_sPlayBestOfArijitSingh.jpg",
            "total_songs": 7
          },
          {
            "post_id": "7",
            "post_type": "playlist",
            "post_title": "Now Playing Pop",
            "post_image": "https://mp3app.viaviweb.in/upload/images/playlist/NowPlayingPop.jpg",
            "total_songs": 4
          },
          {
            "post_id": "5",
            "post_type": "playlist",
            "post_title": "Best Of Romance Hindi",
            "post_image": "https://mp3app.viaviweb.in/upload/images/playlist/BestOfRomanceHindi.jpg",
            "total_songs": 9
          },
          {
            "post_id": "1",
            "post_type": "playlist",
            "post_title": "90s Duets Hindi Songs",
            "post_image": "https://mp3app.viaviweb.in/upload/images/playlist/90sDuetsHindiSongs.jpg",
            "total_songs": 8
          },
          {
            "post_id": "8",
            "post_type": "playlist",
            "post_title": "Punjabse Bollywood",
            "post_image": "https://mp3app.viaviweb.in/upload/images/playlist/PunjabseBollywood.jpg",
            "total_songs": 5
          }
        ]
      },
      {
        "home_id": 4,
        "home_title": "Top Artist",
        "home_type": "artist",
        "home_content": [
          {
            "post_id": "5",
            "post_type": "artist",
            "post_title": "Arijit Singh",
            "post_image": "https://mp3app.viaviweb.in/upload/images/artists/Arijit_Singh.jpg"
          },
          {
            "post_id": "19",
            "post_type": "artist",
            "post_title": "Shreya Ghoshal",
            "post_image": "https://mp3app.viaviweb.in/upload/images/artists/Shreya_Ghoshal.jpg"
          },
          {
            "post_id": "20",
            "post_type": "artist",
            "post_title": "Sidhu Moose Wala",
            "post_image": "https://mp3app.viaviweb.in/upload/images/artists/Sidhu_Moose_Wala.jpg"
          },
          {
            "post_id": "12",
            "post_type": "artist",
            "post_title": "Katy Perry",
            "post_image": "https://mp3app.viaviweb.in/upload/images/artists/Katy_Perry.jpg"
          },
          {
            "post_id": "10",
            "post_type": "artist",
            "post_title": "Guru Randhawa",
            "post_image": "https://mp3app.viaviweb.in/upload/images/artists/Guru_Randhawa.jpg"
          }
        ]
      },
      {
        "home_id": 5,
        "home_title": "Top Albums",
        "home_type": "album",
        "home_content": [
          {
            "post_id": "1",
            "post_type": "album",
            "post_title": "Bhediya",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Bhediya.jpg",
            "total_artist": 2,
            "total_songs": 3
          },
          {
            "post_id": "2",
            "post_type": "album",
            "post_title": "Brahmastra",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Brahmastra.jpg",
            "total_artist": 3,
            "total_songs": 2
          },
          {
            "post_id": "15",
            "post_type": "album",
            "post_title": "Nightbird",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Nightbird.jpg",
            "total_artist": 5,
            "total_songs": 1
          },
          {
            "post_id": "17",
            "post_type": "album",
            "post_title": "Vikram Vedha",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Vikram-Vedha.jpg",
            "total_artist": 5,
            "total_songs": 4
          },
          {
            "post_id": "12",
            "post_type": "album",
            "post_title": "Laal Singh Chaddha",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Laal-Singh-Chaddha.jpg",
            "total_artist": 5,
            "total_songs": 3
          },
          {
            "post_id": "16",
            "post_type": "album",
            "post_title": "Thank God",
            "post_image": "https://mp3app.viaviweb.in/upload/images/album/Thank-God.jpg",
            "total_artist": 3,
            "total_songs": 2
          }
        ]
      }
    ]';


//        dd(json_decode($home));



        return response()->json([
            'ONLINE_MP3_APP'=> [
                'slider'=>$getMusicCategory,
                'recently_songs'=>$getMusic,
                'trending_songs'=>$get_trending_songs,
                'home_sections'=>json_decode($home),
                'status_code'=>200,
                ]
        ]);


    }
}
