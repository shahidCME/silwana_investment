<?php
use Illuminate\Support\Facades\Session;

function flashMessage($status,$message)
{
    $message = '<div class="alert alert-'.$status.' alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>'.$message.'</strong>
                </div>';
    return $message;
}

function dbDateFormat($date='',$isOnlyDate = false){
    if($date != '' && $isOnlyDate==false){
        return date('Y-m-d H:i:s',strtotime($date));
    }
    if($date != '' && $isOnlyDate==true){
        return date('Y-m-d',strtotime($date));
    }
    return date('Y-m-d H:i:s');
}

function admin_login(){
    
    return Session::get('admin_login');
}

function Elq(){
    \DB::enableQueryLog(); // Enable query log
} 

function Plq(){
    dd(\DB::getQueryLog());
} 
function getNotification($isLimit=''){
    
    $query = DB::table('notifications');
    if(admin_login()['role'] == '2'){
        $query->where(['user_id'=>admin_login()['id'],'for_role'=>'2']);
    }
    if(admin_login()['role'] != '2'){
        $query->where('for_role','1');
    }
    if($isLimit){
        $query->limit(10);
    }
    $query->orderBy('id','DESC');
    $data = $query->get();
    
    return $data;
}
function getNationalities(){
    return $nationals = array(
            'Afghan',
            'Albanian',
            'Algerian',
            'American',
            'Andorran',
            'Angolan',
            'Antiguans',
            'Argentinean',
            'Armenian',
            'Australian',
            'Austrian',
            'Azerbaijani',
            'Bahamian',
            'Bahraini',
            'Bangladeshi',
            'Barbadian',
            'Barbudans',
            'Batswana',
            'Belarusian',
            'Belgian',
            'Belizean',
            'Beninese',
            'Bhutanese',
            'Bolivian',
            'Bosnian',
            'Brazilian',
            'British',
            'Bruneian',
            'Bulgarian',
            'Burkinabe',
            'Burmese',
            'Burundian',
            'Cambodian',
            'Cameroonian',
            'Canadian',
            'Cape Verdean',
            'Central African',
            'Chadian',
            'Chilean',
            'Chinese',
            'Colombian',
            'Comoran',
            'Congolese',
            'Costa Rican',
            'Croatian',
            'Cuban',
            'Cypriot',
            'Czech',
            'Danish',
            'Djibouti',
            'Dominican',
            'Dutch',
            'East Timorese',
            'Ecuadorean',
            'Egyptian',
            'Emirian',
            'Equatorial Guinean',
            'Eritrean',
            'Estonian',
            'Ethiopian',
            'Fijian',
            'Filipino',
            'Finnish',
            'French',
            'Gabonese',
            'Gambian',
            'Georgian',
            'German',
            'Ghanaian',
            'Greek',
            'Grenadian',
            'Guatemalan',
            'Guinea-Bissauan',
            'Guinean',
            'Guyanese',
            'Haitian',
            'Herzegovinian',
            'Honduran',
            'Hungarian',
            'I-Kiribati',
            'Icelander',
            'Indian',
            'Indonesian',
            'Iranian',
            'Iraqi',
            'Irish',
            'Israeli',
            'Italian',
            'Ivorian',
            'Jamaican',
            'Japanese',
            'Jordanian',
            'Kazakhstani',
            'Kenyan',
            'Kittian and Nevisian',
            'Kuwaiti',
            'Kyrgyz',
            'Laotian',
            'Latvian',
            'Lebanese',
            'Liberian',
            'Libyan',
            'Liechtensteiner',
            'Lithuanian',
            'Luxembourger',
            'Macedonian',
            'Malagasy',
            'Malawian',
            'Malaysian',
            'Maldivan',
            'Malian',
            'Maltese',
            'Marshallese',
            'Mauritanian',
            'Mauritian',
            'Mexican',
            'Micronesian',
            'Moldovan',
            'Monacan',
            'Mongolian',
            'Moroccan',
            'Mosotho',
            'Motswana',
            'Mozambican',
            'Namibian',
            'Nauruan',
            'Nepalese',
            'New Zealander',
            'Nicaraguan',
            'Nigerian',
            'Nigerien',
            'North Korean',
            'Northern Irish',
            'Norwegian',
            'Omani',
            'Pakistani',
            'Palauan',
            'Panamanian',
            'Papua New Guinean',
            'Paraguayan',
            'Peruvian',
            'Polish',
            'Portuguese',
            'Qatari',
            'Romanian',
            'Russian',
            'Rwandan',
            'Saint Lucian',
            'Salvadoran',
            'Samoan',
            'San Marinese',
            'Sao Tomean',
            'Saudi',
            'Scottish',
            'Senegalese',
            'Serbian',
            'Seychellois',
            'Sierra Leonean',
            'Singaporean',
            'Slovakian',
            'Slovenian',
            'Solomon Islander',
            'Somali',
            'South African',
            'South Korean',
            'Spanish',
            'Sri Lankan',
            'Sudanese',
            'Surinamer',
            'Swazi',
            'Swedish',
            'Swiss',
            'Syrian',
            'Taiwanese',
            'Tajik',
            'Tanzanian',
            'Thai',
            'Togolese',
            'Tongan',
            'Trinidadian/Tobagonian',
            'Tunisian',
            'Turkish',
            'Tuvaluan',
            'Ugandan',
            'Ukrainian',
            'Uruguayan',
            'Uzbekistani',
            'Venezuelan',
            'Vietnamese',
            'Welsh',
            'Yemenite',
            'Zambian',
            'Zimbabwean'
        );
}

function getContractTemplate(){
    return [
        'diamond'=>"Diamond Contract"
    ];
}
function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? '' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . '';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? '' . $list3[$levels] . '' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    // dd($words);
    return trim(ucwords(implode(' ', $words)));
}


function send_notification_FCM($notification_id, $title, $message, $id,$type) {
 
    $accesstoken = "AAAAIj7AKbE:APA91bEvbVvPbcRUeBJwdkjUUfDrwJdwvU5RErIDUXc67DRzBIUCXS4AcmZPK-EYWH8pwUHSIuwDV3PsRdFm9A0tSvUAjnn7oVfSsJOPotsp8mcCTe1tSDxO72bAegMR9keZV8Oa_kXR";
    
    $URL = 'https://fcm.googleapis.com/fcm/send';
 
 
        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                "priority" : "high"
                },
 
          }';
        // print_r($post_data);die;
 
    $crl = curl_init();
 
    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization:key=' . $accesstoken;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
 
    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
 
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
 
    $rest = curl_exec($crl);
    // dd($rest);
    if ($rest === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        // print_r('Curl error: ' . curl_error($crl));
        $result_noti = 0;
    } else {
 
        $result_noti = 1;
    }
 
    //curl_close($crl);
    //print_r($result_noti);die;
    return $result_noti;
}
if(!function_exists('GetDialcodelist'))
        {
            function GetDialcodelist()
            {
                $arr =    array(
                    "+213"=>"Algeria (+213)",
                    "+376"=>"Andorra (+376)",
                    "+244"=>"Angola (+244)",
                    "+1264"=>"Anguilla (+1264)",
                    "+1268"=>"Antigua & Barbuda (+1268)",
                    "+54"=>"Argentina (+54)",
                    "+374"=>"Armenia (+374)",
                    "+297"=>"Aruba (+297)",
                    "+61"=>"Australia (+61)",
                    "+43"=>"Austria (+43)",
                    "+994"=>"Azerbaijan (+994)",
                    "+1242"=>"Bahamas (+1242)",
                    "+973"=>"Bahrain (+973)",
                    "+880"=>"Bangladesh (+880)",
                    "+1246"=>"Barbados (+1246)",
                    "+375"=>"Belarus (+375)",
                    "+32"=>"Belgium (+32)",
                    "+501"=>"Belize (+501)",
                    "+229"=>"Benin (+229)",
                    "+1441"=>"Bermuda (+1441)",
                    "+975"=>"Bhutan (+975)",
                    "+591"=>"Bolivia (+591)",
                    "+387"=>"Bosnia Herzegovina (+387)",
                    "+267"=>"Botswana (+267)",
                    "+55"=>"Brazil (+55)",
                    "+673"=>"Brunei (+673)",
                    "+359"=>"Bulgaria (+359)",
                    "+226"=>"Burkina Faso (+226)",
                    "+257"=>"Burundi (+257)",
                    "+855"=>"Cambodia (+855)",
                    "+237"=>"Cameroon (+237)",
                    "+1"=>"Canada (+1)",
                    "+238"=>"Cape Verde Islands (+238)",
                    "+1345"=>"Cayman Islands (+1345)",
                    "+236"=>"Central African Republic (+236)",
                    "+56"=>"Chile (+56)",
                    "+86"=>"China (+86)",
                    "+57"=>"Colombia (+57)",
                    "+269"=>"Comoros (+269)",
                    "+242"=>"Congo (+242)",
                    "+682"=>"Cook Islands (+682)",
                    "+506"=>"Costa Rica (+506)",
                    "+385"=>"Croatia (+385)",
                    "+53"=>"Cuba (+53)",
                    "+90392"=>"Cyprus North (+90392)",
                    "+357"=>"Cyprus South (+357)",
                    "+42"=>"Czech Republic (+42)",
                    "+45"=>"Denmark (+45)",
                    "+253"=>"Djibouti (+253)",
                    "+1809"=>"Dominica (+1809)",
                    "+1809"=>"Dominican Republic (+1809)",
                    "+593"=>"Ecuador (+593)",
                    "+20"=>"Egypt (+20)",
                    "+503"=>"El Salvador (+503)",
                    "+240"=>"Equatorial Guinea (+240)",
                    "+291"=>"Eritrea (+291)",
                    "+372"=>"Estonia (+372)",
                    "+251"=>"Ethiopia (+251)",
                    "+500"=>"Falkland Islands (+500)",
                    "+298"=>"Faroe Islands (+298)",
                    "+679"=>"Fiji (+679)",
                    "+358"=>"Finland (+358)",
                    "+33"=>"France (+33)",
                    "+594"=>"French Guiana (+594)",
                    "+689"=>"French Polynesia (+689)",
                    "+241"=>"Gabon (+241)",
                    "+220"=>"Gambia (+220)",
                    "+7880"=>"Georgia (+7880)",
                    "+49"=>"Germany (+49)",
                    "+233"=>"Ghana (+233)",
                    "+350"=>"Gibraltar (+350)",
                    "+30"=>"Greece (+30)",
                    "+299"=>"Greenland (+299)",
                    "+1473"=>"Grenada (+1473)",
                    "+590"=>"Guadeloupe (+590)",
                    "+671"=>"Guam (+671)",
                    "+502"=>"Guatemala (+502)",
                    "+224"=>"Guinea (+224)",
                    "+245"=>"Guinea - Bissau (+245)",
                    "+592"=>"Guyana (+592)",
                    "+509"=>"Haiti (+509)",
                    "+504"=>"Honduras (+504)",
                    "+852"=>"Hong Kong (+852)",
                    "+36"=>"Hungary (+36)",
                    "+354"=>"Iceland (+354)",
                    "+91"=>"India (+91)",
                    "+62"=>"Indonesia (+62)",
                    "+98"=>"Iran (+98)",
                    "+964"=>"Iraq (+964)",
                    "+353"=>"Ireland (+353)",
                    "+972"=>"Israel (+972)",
                    "+39"=>"Italy (+39)",
                    "+1876"=>"Jamaica (+1876)",
                    "+81"=>"Japan (+81)",
                    "+962"=>"Jordan (+962)",
                    "+7"=>"Kazakhstan (+7)",
                    "+254"=>"Kenya (+254)",
                    "+686"=>"Kiribati (+686)",
                    "+850"=>"Korea North (+850)",
                    "+82"=>"Korea South (+82)",
                    "+965"=>"Kuwait (+965)",
                    "+996"=>"Kyrgyzstan (+996)",
                    "+856"=>"Laos (+856)",
                    "+371"=>"Latvia (+371)",
                    "+961"=>"Lebanon (+961)",
                    "+266"=>"Lesotho (+266)",
                    "+231"=>"Liberia (+231)",
                    "+218"=>"Libya (+218)",
                    "+417"=>"Liechtenstein (+417)",
                    "+370"=>"Lithuania (+370)",
                    "+352"=>"Luxembourg (+352)",
                    "+853"=>"Macao (+853)",
                    "+389"=>"Macedonia (+389)",
                    "+261"=>"Madagascar (+261)",
                    "+265"=>"Malawi (+265)",
                    "+60"=>"Malaysia (+60)",
                    "+960"=>"Maldives (+960)",
                    "+223"=>"Mali (+223)",
                    "+356"=>"Malta (+356)",
                    "+692"=>"Marshall Islands (+692)",
                    "+596"=>"Martinique (+596)",
                    "+222"=>"Mauritania (+222)",
                    "+269"=>"Mayotte (+269)",
                    "+52"=>"Mexico (+52)",
                    "+691"=>"Micronesia (+691)",
                    "+373"=>"Moldova (+373)",
                    "+377"=>"Monaco (+377)",
                    "+976"=>"Mongolia (+976)",
                    "+1664"=>"Montserrat (+1664)",
                    "+212"=>"Morocco (+212)",
                    "+258"=>"Mozambique (+258)",
                    "+95"=>"Myanmar (+95)",
                    "+264"=>"Namibia (+264)",
                    "+674"=>"Nauru (+674)",
                    "+977"=>"Nepal (+977)",
                    "+31"=>"Netherlands (+31)",
                    "+687"=>"New Caledonia (+687)",
                    "+64"=>"New Zealand (+64)",
                    "+505"=>"Nicaragua (+505)",
                    "+227"=>"Niger (+227)",
                    "+234"=>"Nigeria (+234)",
                    "+683"=>"Niue (+683)",
                    "+672"=>"Norfolk Islands (+672)",
                    "+670"=>"Northern Marianas (+670)",
                    "+47"=>"Norway (+47)",
                    "+968"=>"Oman (+968)",
                    "+680"=>"Palau (+680)",
                    "+507"=>"Panama (+507)",
                    "+675"=>"Papua New Guinea (+675)",
                    "+595"=>"Paraguay (+595)",
                    "+51"=>"Peru (+51)",
                    "+63"=>"Philippines (+63)",
                    "+48"=>"Poland (+48)",
                    "+351"=>"Portugal (+351)",
                    "+1787"=>"Puerto Rico (+1787)",
                    "+974"=>"Qatar (+974)",
                    "+262"=>"Reunion (+262)",
                    "+40"=>"Romania (+40)",
                    "+7"=>"Russia (+7)",
                    "+250"=>"Rwanda (+250)",
                    "+378"=>"San Marino (+378)",
                    "+239"=>"Sao Tome & Principe (+239)",
                    "+966"=>"Saudi Arabia (+966)",
                    "+221"=>"Senegal (+221)",
                    "+381"=>"Serbia (+381)",
                    "+248"=>"Seychelles (+248)",
                    "+232"=>"Sierra Leone (+232)",
                    "+65"=>"Singapore (+65)",
                    "+421"=>"Slovak Republic (+421)",
                    "+386"=>"Slovenia (+386)",
                    "+677"=>"Solomon Islands (+677)",
                    "+252"=>"Somalia (+252)",
                    "+27"=>"South Africa (+27)",
                    "+34"=>"Spain (+34)",
                    "+94"=>"Sri Lanka (+94)",
                    "+290"=>"St. Helena (+290)",
                    "+1869"=>"St. Kitts (+1869)",
                    "+1758"=>"St. Lucia (+1758)",
                    "+249"=>"Sudan (+249)",
                    "+597"=>"Suriname (+597)",
                    "+268"=>"Swaziland (+268)",
                    "+46"=>"Sweden (+46)",
                    "+41"=>"Switzerland (+41)",
                    "+963"=>"Syria (+963)",
                    "+886"=>"Taiwan (+886)",
                    "+7"=>"Tajikstan (+7)",
                    "+66"=>"Thailand (+66)",
                    "+228"=>"Togo (+228)",
                    "+676"=>"Tonga (+676)",
                    "+1868"=>"Trinidad & Tobago (+1868)",
                    "+216"=>"Tunisia (+216)",
                    "+90"=>"Turkey (+90)",
                    "+7"=>"Turkmenistan (+7)",
                    "+993"=>"Turkmenistan (+993)",
                    "+1649"=>"Turks & Caicos Islands (+1649)",
                    "+688"=>"Tuvalu (+688)",
                    "+44"=>"UK (+44)",
                    "+1"=>"USA (+1)",
                    "+256"=>"Uganda (+256)",
                    "+380"=>"Ukraine (+380)",
                    "+971"=>"United Arab Emirates (+971)",
                    "+598"=>"Uruguay (+598)",
                    "+7"=>"Uzbekistan (+7)",
                    "+678"=>"Vanuatu (+678)",
                    "+379"=>"Vatican City (+379)",
                    "+58"=>"Venezuela (+58)",
                    "+84"=>"Vietnam (+84)",
                    "+84"=>"Virgin Islands - British (+1284)",
                    "+84"=>"Virgin Islands - US (+1340)",
                    "+681"=>"Wallis & Futuna (+681)",
                    "+969"=>"Yemen (North)(+969)",
                    "+967"=>"Yemen (South)(+967)",
                    "+260"=>"Zambia (+260)",
                    "+263"=>"Zimbabwe (+263)",
                );
                return $arr;
            }   
        }


?>