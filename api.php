<?php
require 'rb-mysql.php';

// define("DB_HOST", "localhost"); //Сервер базы данных
// define("DB_USERNAME", "vk-mini-app"); //Пользователь базы данных
// define("DB_PASSWORD", "vk-mini-app"); //Пароль от пользователя базы данных
// define("DB_NAME", "vk-mini-app"); //Имя базы данных

define("DB_HOST", ""); //Сервер базы данных
define("DB_USERNAME", ""); //Пользователь базы данных
define("DB_PASSWORD", ""); //Пароль от пользователя базы данных
define("DB_NAME", ""); //Имя базы данных
define("APP_SECRET", ""); //Защищенный ключ приложения
define("SERVICE_KEY", "");
define("ENDPOINT", "https://api.vk.com/method/");



if(!R::testConnection()) {
    R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
}

$pets = [
    1 => [
        'name' => 'Улитка',
        'price' => 1000,
        'min' => 1,
        'max' => 10
    ],
    2 => [
        'name' => 'Лягушка',
        'price' => 25000,
        'min' => 100,
        'max' => 1000
    ],
    3 => [
        'name' => 'Заяц',
        'price' => 95000,
        'min' => 1000,
        'max' => 5000
    ],
    4 => [
        'name' => 'Лиса',
        'price' => 150000,
        'min' => 5000,
        'max' => 10000
    ],
    5 => [
        'name' => 'Панда',
        'price' => 250000,
        'min' => 10000,
        'max' => 25000
    ]
];

$houses = [
    1 => [
        'name' => 'Старый домик в лесу',
        'price' => 25000,
        'min' => 100,
        'max' => 250
    ],
    2 => [
        'name' => 'Домик возле поселения',
        'price' => 50000,
        'min' => 250,
        'max' => 850
    ],
    3 => [
        'name' => 'Большой дом возле реки',
        'price' => 165000,
        'min' => 850,
        'max' => 2500
    ],
    4 => [
        'name' => 'Дом в городе',
        'price' => 245000,
        'min' => 2500,
        'max' => 7500
    ],
    5 => [
        'name' => 'Дом с джакузи',
        'price' => 555000,
        'min' => 7500,
        'max' => 25000
    ],
    6 => [
        'name' => 'Дом возле моря',
        'price' => 785000,
        'min' => 25000,
        'max' => 29000
    ],
    7 => [
        'name' => 'Дом отдыха',
        'price' => 925000,
        'min' => 29000,
        'max' => 31000
    ]
];

function getUser($user, $arr = null) {
    global $pets, $houses;
    $user['pet_name'] = isset($pets[$user['pet']]['name']) ? $pets[$user['pet']]['name'] : null; 
    $user['house_name'] = isset($houses[$user['house']]['name']) ? $houses[$user['house']]['name'] : null; 
    if(isset($arr)) {
        $user['status'] = $arr['status'];
        $user['msg'] = $arr['msg'];
    }
    return $user;
}

function api($method, $params) {
    $params['access_token'] = SERVICE_KEY;
    $url = ENDPOINT . "{$method}?" . http_build_query($params);

    return json_decode(file_get_contents($url), true);
}

$url = $_SERVER['HTTP_REFERER'];
$client_secret = APP_SECRET;
parse_str(parse_url($url, PHP_URL_QUERY), $query);

$query_params = []; 
parse_str(parse_url($url, PHP_URL_QUERY), $query_params); // Получаем query-параметры из URL 

$sign_params = []; 
foreach ($query_params as $name => $value) { 
    if (strpos($name, 'vk_') !== 0) { // Получаем только vk параметры из query 
      continue; 
    } 

$sign_params[$name] = $value; 
} 

ksort($sign_params); // Сортируем массив по ключам 
$sign_params_query = http_build_query($sign_params); // Формируем строку вида "param_name1=value&param_name2=value" 
$sign = rtrim(strtr(base64_encode(hash_hmac('sha256', $sign_params_query, $client_secret, true)), '+/', '-_'), '='); // Получаем хеш-код от строки, используя защищеный ключ приложения. Генерация на основе метода HMAC. 

$status = $sign === $query_params['sign']; // Сравниваем полученную подпись со значением параметра 'sign' 
if($status === false) die("No hacker");

$data = $_POST;
$data['user_vk'] = $query['vk_user_id'];
$method = $_POST['method'];

switch($method) {
    case 'user.create':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);
        if($user==null) {
            $params = [
                'user_id'       => $data['user_vk'],
                'fields'        => 'photo_200_orig',
                'v'             => '5.95'
            ];

            $response = api('users.get', $params)['response'][0];

            $user = R::dispense('users');
            $user->user_vk_id = $response['id'];
            $user->nickname   = "Player {$response['id']}";
            $user->first_name = $response['first_name'];
            $user->last_name  = $response['last_name'];
            $user->clicks     = 0;
            $user->reputation = 0;
            $user->photo      = $response['photo_200_orig'];
            $user->pet        = 0;
            $user->house      = 0;
            $user = R::load('users', R::store($user));
        }
        $array = getUser($user);
        die(json_encode($array));
        break;
    case 'user.update.click':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        $user->clicks = (float) $user->clicks + (float) $user->speed;

        $user = R::load('users', R::store($user));
        $array = getUser($user);
        die(json_encode($array));
        break;
    case 'user.update.reputation':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if (($data['reputation_new'] * 50000) <= $user->clicks) {
            $user->reputation = (int)$user->reputation + (int)$data['reputation_new'];
            $user->clicks = (float) $user->clicks - (int) $data['reputation_new'] * 50000;
            $user = R::load('users', R::store($user));
            $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно приобрели рейтинг, теперь у вас {$user->reputation} рейтинга"]);
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "У вас недостаточно коинов на счету, для покупки рейтинга"]);
        }
        
        $array = getUser($user);
        die(json_encode($array));
        break;
    case 'user.update.nickname':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if(isset($data['nickname']) OR $data['nickname'] !== null OR $data['nickname'] !== "" OR !empty($data['nickname'])) {
            $user->nickname = $data['nickname'];
            $user = R::load('users', R::store($user));
            $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно сменили ник, ваш новый ник {$user->nickname}"]);
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "К сожалению вы не можете поставить себе пустой ник"]);
        }
        
        $array = getUser($user);
        die(json_encode($array));
        break;
    case 'user.buy.pet':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if($user->pet <= 0) {
            if($user->clicks >= $pets[$data['pet_id']]['price']) {
                $user->pet = $data['pet_id'];
                $user->clicks = $user->clicks - $pets[$data['pet_id']]['price'];
                $user = R::load('users', R::store($user));
                $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно купили питомца {$pets[$data['pet_id']]['name']} за {$pets[$data['pet_id']]['price']} коинов"]);
            }
            else {
                $sum = $pets[$data['pet_id']]['price'] - $user->clicks;
                $array = getUser($user, ['status' => 'error', 'msg' => "Не удалось приобрести питомца, на вашем балансе не хватает {$sum} коинов"]);
            }
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "У вас уже есть питомец {$pets[$user->pet]['name']}"]);
        }

        die(json_encode($array));
        break;
    case 'user.sell.pet':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if($user->pet > 0) {
            $sum = ($pets[$data['pet_id']]['price'] / 100) * 90;
            $user->pet = 0;
            $user->clicks = $user->clicks + $sum;
            $user = R::load('users', R::store($user));
            $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно продали питомца за {$sum} коинов"]);
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "У вас нет купленых питомцев"]);
        }
        
        
        die(json_encode($array));
        break;

    case 'user.pohod.pet':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);
        $time = time() - strtotime($user->last_pet_pohod);
        if($time >= 60*60) {
            if($user->pet > 0) {
                $min = $pets[$user->pet]['min'];
                $max = $pets[$user->pet]['max'];
                $coins = rand($min, $max);
                $user->clicks = $user->clicks + $coins;
                $user->last_pet_pohod = R::isoDateTime();
                $user = R::load('users', R::store($user));
                $array = getUser($user, ['status' => 'success', 'msg' => "В результате похода вы заработали {$coins} коинов"]);
            } else {
                $array = getUser($user, ['status' => 'error', 'msg' => "У вас нет купленых питомцев"]);
            }
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "Вы уже отправляли питомца в поход, попробуйте чуть позже"]);
        }
        
        die(json_encode($array));
        break;
        case 'user.buy.house':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if($user->house <= 0) {
            if($user->clicks >= $houses[$data['house_id']]['price']) {
                $user->house = $data['house_id'];
                $user->clicks = $user->clicks - $houses[$data['house_id']]['price'];
                $user = R::load('users', R::store($user));
                $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно купили дом {$houses[$data['house_id']]['name']} за {$houses[$data['house_id']]['price']} коинов"]);
            }
            else {
                $sum = $houses[$data['house_id']]['price'] - $user->clicks;
                $array = getUser($user, ['status' => 'error', 'msg' => "Не удалось приобрести дом, на вашем балансе не хватает {$sum} коинов"]);
            }
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "У вас уже есть дом {$houses[$user->house]['name']}"]);
        }

        die(json_encode($array));
        break;
    case 'user.sell.house':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if($user->house > 0) {
            $sum = ($houses[$data['house_id']]['price'] / 100) * 90;
            $user->house = 0;
            $user->clicks = $user->clicks + $sum;
            $user = R::load('users', R::store($user));
            $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно продали дом за {$sum} коинов"]);
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "У вас нет купленного дома"]);
        }
        
        
        die(json_encode($array));
        break;
    case 'user.pohod.house':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);
        $time = time() - strtotime($user->last_house_pohod);
        if($time >= 60*60) {
            if($user->house > 0) {
                $min = $houses[$user->house]['min'];
                $max = $houses[$user->house]['max'];
                $coins = rand($min, $max);
                $user->clicks = $user->clicks + $coins;
                $user->last_house_pohod = R::isoDateTime();
                $user = R::load('users', R::store($user));
                $array = getUser($user, ['status' => 'success', 'msg' => "В результате сдачи дома в аренду вы заработали {$coins} коинов"]);
            } else {
                $array = getUser($user, ['status' => 'error', 'msg' => "У вас нет купленного дома"]);
            }
        } else {
            $array = getUser($user, ['status' => 'error', 'msg' => "Вы уже сдавали дом в аренду, попробуйте чуть позже"]);
        }
        
        die(json_encode($array));
        break;
    case 'user.buy.upgrade':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        $upgrade = R::findOne('upgrades', 'type = ? AND name = ?', [
            $data['type'], $data['up_name']
        ]);


        $upgrade_history = R::findOne('history', 'user_id = ? AND upgrade_id = ?', [
            $user->id, $upgrade->id
        ]);

        if(isset($upgrade)) {
            $price = isset($upgrade_history) ? $upgrade_history->price : $upgrade->price;
            if($user->clicks >= $price) {
               if($data['type'] === "0")  $user->speed = (float) $user->speed + (float) $upgrade->speed;
               else  $user->aspeed = (float) $user->aspeed + (float) $upgrade->speed;
                $user->clicks = (float) $user->clicks - (float) $upgrade->price;
                $user = R::load('users', R::store($user));            
                $price_new = round((float) $price + ((float) $price / 100) * 40, 3);    
                if($upgrade_history===null) {
                    $upgrade_history = R::dispense('history');
                    $upgrade_history->user_id = $user->id;
                    $upgrade_history->upgrade_id = $upgrade->id;
                    $upgrade_history->price = $price_new;
                    $upgrade_history = R::load('history', R::store($upgrade_history));
               } else {
                    $upgrade_history->price = $price_new;
                    $upgrade_history = R::load('history', R::store($upgrade_history));
               }
            } else {
                //$array = getUser($user, ['status' => 'error', 'msg' => "На вашем балансе недостаточно коинов"]);
            }
        }
        //$array = getUser($user);
        die(json_encode($user));
        break;
        case 'user.send.transfer':
        if(!isset($data['toid'])) return die(json_encode(getUser($user, ['status' => 'error', 'msg' => "Введите id"])));
        $to = R::findOne('users', 'user_vk_id = ?', [
            $data['toid']
        ]);
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

		if($data['count'] <= 0) {
		$array = getUser($user, ['status' => 'error', 'msg' => "Сумма перевода должна быть положительной"]);
		} else {
        if($data['toid'] == $data['user_vk']) {
		$array = getUser($user, ['status' => 'error', 'msg' => "Нельзя переводить самому себе"]);
		} else {
        if(!$to) {
            $array = getUser($user, ['status' => 'error', 'msg' => "Такого пользователя нет в базе"]);
        } else {
           

            if($user->clicks >= $data['count']) {
                
                $user->clicks = $user->clicks - $data['count'];
                $to->clicks = $to->clicks + $data['count'];


                
                if($to->history == 0) {
                    
                    $to->history1 = "{$user->first_name} {$user->last_name}";

                    $to->history = 1;
                    $to->h_id1 = $data['user_vk'];
                    $to->h_s1 = $data['count'];
                }
                if($to->history == 1) {
                    
                    $to->history2 = "{$user->first_name} {$user->last_name}";

                    $to->history = 2;
                    $to->h_id2 = $data['user_vk'];
                    $to->h_s2 = $data['count'];
                }


                if($to->history == 2) {
                    
                    $to->history3 = "{$user->first_name} {$user->last_name}";

                    $to->history = 3;
                    $to->h_id3 = $data['user_vk'];
                    $to->h_s3 = $data['count'];
                }


                if($to->history == 3) {
                    
                    $to->history4 = "{$user->first_name} {$user->last_name}";

                    $to->history = 4;
                    $to->h_id4 = $data['user_vk'];
                    $to->h_s4 = $data['count'];
                }


                if($to->history == 4) {

                    $to->history5 = "{$user->first_name} {$user->last_name}";

                    $to->history = 5;
                    $to->h_id5 = $data['user_vk'];
                    $to->h_s5 = $data['count'];
                }


                if($to->history == 5) {
                    
                    $to->history6 = "{$user->first_name} {$user->last_name}";

                    $to->history = 6;
                    $to->h_id6 = $data['user_vk'];
                    $to->h_s6 = $data['count'];
                }


                if($to->history == 6) {
                    
                    $to->history7 = "{$user->first_name} {$user->last_name}";

                    $to->history = 7;
                    $to->h_id7 = $data['user_vk'];
                    $to->h_s7 = $data['count'];
                }


                if($to->history == 7) {
                    
                    $to->history8 = "{$user->first_name} {$user->last_name}";

                    $to->history = 8;
                    $to->h_id8 = $data['user_vk'];
                    $to->h_s8 = $data['count'];
                }


                if($to->history == 8) {
                    
                    $to->history9 = "{$user->first_name} {$user->last_name}";

                    $to->history = 9;
                    $to->h_id9 = $data['user_vk'];
                    $to->h_s9 = $data['count'];
                }


                if($to->history == 9) {
                    
                    $to->history10 = "{$user->first_name} {$user->last_name}";

                    $to->history = 10;
                    $to->h_id10 = $data['user_vk'];
                    $to->h_s10 = $data['count'];
                }


                if($to->history == 10) {

                    

                    $to->history1 = $to->history2;
                    $to->history2 = $to->history3;
                    $to->history3 = $to->history4;
                    $to->history4 = $to->history5;
                    $to->history5 = $to->history6;
                    $to->history6 = $to->history7;
                    $to->history7 = $to->history8;
                    $to->history8 = $to->history9;
                    $to->history9 = $to->history10;
                    $to->history10 = "{$user->first_name} {$user->last_name}";

                    $to->h_id1 = $to->h_id2;
                    $to->h_id2 = $to->h_id3;
                    $to->h_id3 = $to->h_id4;
                    $to->h_id4 = $to->h_id5;
                    $to->h_id5 = $to->h_id6;
                    $to->h_id6 = $to->h_id7;
                    $to->h_id7 = $to->h_id8;
                    $to->h_id8 = $to->h_id9;
                    $to->h_id9 = $to->h_id10;
                    $to->h_id10 = $data['user_vk'];

                    $to->h_s1 = $to->h_s2;
                    $to->h_s2 = $to->h_s3;
                    $to->h_s3 = $to->h_s4;
                    $to->h_s4 = $to->h_s5;
                    $to->h_s5 = $to->h_s6;
                    $to->h_s6 = $to->h_s7;
                    $to->h_s7 = $to->h_s8;
                    $to->h_s8 = $to->h_s9;
                    $to->h_s9 = $to->h_s10;
                    $to->h_s10 = $data['count'];
                    
                }

                //////////////////////////////////////////////




                if($user->viv == 0) {
                    
                    $user->v1 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 1;
                    $user->v_id1 = $data['toid'];
                    $user->v_s1 = $data['count'];
                }

                if($user->viv == 1) {
                    
                    $user->v2 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 2;
                    $user->v_id2 = $data['toid'];
                    $user->v_s2 = $data['count'];
                }


                if($user->viv == 2) {
                    
                    $user->v3 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 3;
                    $user->v_id3 = $data['toid'];
                    $user->v_s3 = $data['count'];
                }


                if($user->viv == 3) {
                    
                    $user->v4 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 4;
                    $user->v_id4 = $data['toid'];
                    $user->v_s4 = $data['count'];
                }


                if($user->viv == 4) {

                    $user->v5 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 5;
                    $user->v_id5 = $data['toid'];
                    $user->v_s5 = $data['count'];
                }


                if($user->viv == 5) {
                    
                    $user->v6 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 6;
                    $user->v_id6 = $data['toid'];
                    $user->v_s6 = $data['count'];
                }


                if($user->viv == 6) {
                    
                    $user->v7 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 7;
                    $user->v_id7 = $data['toid'];
                    $user->v_s7 = $data['count'];
                }


                if($user->viv == 7) {
                    
                    $user->v8 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 8;
                    $user->v_id8 = $data['toid'];
                    $user->v_s8 = $data['count'];
                }


                if($user->viv == 8) {
                    
                    $user->v9 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 9;
                    $user->v_id9 = $data['toid'];
                    $user->v_s9 = $data['count'];
                }


                if($user->viv == 9) {
                    
                    $user->v10 = "{$to->first_name} {$to->last_name}";

                    $user->viv = 10;
                    $user->v_id10 = $data['toid'];
                    $user->v_s10 = $data['count'];
                }


                if($user->viv == 10) {

                    

                    $user->v1 = $user->v2;
                    $user->v2 = $user->v3;
                    $user->v3 = $user->v4;
                    $user->v4 = $user->v5;
                    $user->v5 = $user->v6;
                    $user->v6 = $user->v7;
                    $user->v7 = $user->v8;
                    $user->v8 = $user->v9;
                    $user->v9 = $user->v10;
                    $user->v10 = "{$to->first_name} {$to->last_name}";

                    $user->v_id1 = $user->v_id2;
                    $user->v_id2 = $user->v_id3;
                    $user->v_id3 = $user->v_id4;
                    $user->v_id4 = $user->v_id5;
                    $user->v_id5 = $user->v_id6;
                    $user->v_id6 = $user->v_id7;
                    $user->v_id7 = $user->v_id8;
                    $user->v_id8 = $user->v_id9;
                    $user->v_id9 = $user->v_id10;
                    $user->v_id10 = $data['toid'];

                    $user->v_s1 = $user->v_s2;
                    $user->v_s2 = $user->v_s3;
                    $user->v_s3 = $user->v_s4;
                    $user->v_s4 = $user->v_s5;
                    $user->v_s5 = $user->v_s6;
                    $user->v_s6 = $user->v_s7;
                    $user->v_s7 = $user->v_s8;
                    $user->v_s8 = $user->v_s9;
                    $user->v_s9 = $user->v_s10;
                    $user->v_s10 = $data['count'];
                    
                }
                
                
                R::store($to);
                $user = R::load('users', R::store($user));
                $array = getUser($user, ['status' => 'success', 'msg' => "Вы успешно перевели {$data['count']} коинов, на счет {$data['toid']}"]);

                

            } else {
                $array = getUser($user, ['status' => 'error', 'msg' => "На вашем балансе недостаточно коинов"]);
            }
          }
        }    
      } 

        $array = getUser($user);
        die(json_encode($array));
        break;
        case 'get.users.top.clicks':
        $usersC = R::findAll('users', "ORDER BY `clicks` DESC LIMIT 100");
        $i = 0;
        $us = [];
        foreach ($usersC as $key => $value) {
            if($i < 100) {
                $us[$i] = $value;
            }  
            $i++;
        }

        // $result = array_filter($us, function($k) {
        //     return (int) $k['user_vk_id'] === (int) $data['user_vk'];
        // });
        // var_dump($result);
        // $key = (int) array_keys($result)[0];
        // $my = [
        //     'top_id' => $key,
        //     'photo'  => $result[$key]['photo'],
        //     'clicks' => $result[$key]['clicks'],
        //     'user_vk_id' => $result[$key]['user_vk_id'],
        //     'first_name' => $result[$key]['first_name'],
        //     'last_name' => $result[$key]['last_name']
        // ];

        // die(json_encode([
        //     'top' => $us,
        //     'my'  => $my
        // ]));
        die(json_encode($us));
        break;
    case 'get.users.top.reputation':
        $usersP = R::findAll('users', "ORDER BY `reputation` DESC");
        $us = [];
        $i = 0;
        foreach ($usersP as $key => $value) {
            $us[$i] = $value;
            $i++;
        }
        die(json_encode($us));
        break;
    case 'get.upgrades.one':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);
        if(!isset($user)) return;
        $upgrades = R::findAll('upgrades', "type = 0 ORDER BY `id` ASC");
        $usu = [];
        $i = 0;
        foreach ($upgrades as $key => $value) {
            $hist = R::findOne('history', "upgrade_id = ? AND user_id = ?", [$value->id, $user->id]);

            $usu[$key] = [
                'name' => $value->name,
                'price' => $price = isset($hist) ? $hist->price : $value->price,
                'speed' => $value->speed,
                'type' => $value->type
            ];
        }

        die(json_encode($usu));
        break;
    case 'get.upgrades.two':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);
        if(!isset($user)) return;
        $upgrades = R::findAll('upgrades', "type = 1 ORDER BY `id` ASC");
        $usu = [];
        foreach ($upgrades as $key => $value) {
            $hist = R::findOne('history', "upgrade_id = ? AND user_id = ?", [$value->id, $user->id]);

            $usu[$key] = [
                'name' => $value->name,
                'price' => $price = isset($hist) ? $hist->price : $value->price,
                'speed' => $value->speed,
                'type' => $value->type
            ];
        }

        die(json_encode($usu));
        break;
    case 'user.auto.mine':
        $user = R::findOne('users', 'user_vk_id = ?', [
            $data['user_vk']
        ]);

        if(!isset($user)) return;

        $user->clicks = (float) $user->clicks + (float) $user->aspeed; 
        $user = R::load('users', R::store($user));

        $array = getUser($user);
        die(json_encode($array));
        break;
    default:
        die(json_encode($data));
        break;
}