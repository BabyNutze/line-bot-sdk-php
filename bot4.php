<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include composer autoload
require_once 'vendor/autoload.php';

// การตั้งเกี่ยวกับ bot
require_once 'bot_settings.php';
require_once("db.php");
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");

///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');

// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);

if (!is_null($events)) {
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $userID = $events['events'][0]['source']['userId'];
    $sourceType = $events['events'][0]['source']['type'];
    $is_postback = null;
    $is_message = null;
    if (isset($events['events'][0]) && array_key_exists('message', $events['events'][0])) {
        $is_message = true;
        $typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text'];
        $idMessage = $events['events'][0]['message']['id'];
    }
    if (isset($events['events'][0]) && array_key_exists('postback', $events['events'][0])) {
        $is_postback = true;
        $dataPostback = null;
        parse_str($events['events'][0]['postback']['data'], $dataPostback);
        ;
        $paramPostback = null;
        if (array_key_exists('params', $events['events'][0]['postback'])) {
            if (array_key_exists('date', $events['events'][0]['postback']['params'])) {
                $paramPostback = $events['events'][0]['postback']['params']['date'];
            }
            if (array_key_exists('time', $events['events'][0]['postback']['params'])) {
                $paramPostback = $events['events'][0]['postback']['params']['time'];
            }
            if (array_key_exists('datetime', $events['events'][0]['postback']['params'])) {
                $paramPostback = $events['events'][0]['postback']['params']['datetime'];
            }
        }
    }
    if (!is_null($is_postback)) {
        $textReplyMessage = "บันทึกการรับข้อมูล";
        if (is_array($dataPostback)) {
            $pb = json_encode($dataPostback);
            $pp = json_decode($pb, true);
            $field = $pp["field"];

            $sql = "UPDATE contact SET followfield = $field WHERE userid = '$userID' ";

            if (mysqli_query($conn, $sql)) {
                $sql = "SELECT * FROM contact WHERE userid = '$userID' ";
                $result = mysqli_query($conn, $sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    if ($row = $result->fetch_assoc()) {
                        $lname = $row[ "name" ];
                    }
                } else {
                    $lname = "ผู้ใช้งาน";
                }

                if ($pp["field"] == 1 && $pp["action"] == "field") {
                    $txt = "สาขาโยธา";
                }
                if ($pp["field"] == 2 && $pp["action"] == "field") {
                    $txt = "สาขาเครื่องกล";
                }
                if ($pp["field"] == 3 && $pp["action"] == "field") {
                    $txt = "สาขาไฟฟ้ากำลัง";
                }
                if ($pp["field"] == 4 && $pp["action"] == "field") {
                    $txt = "สาขาไฟฟ้าสื่อสาร";
                }
                if ($pp["field"] == 5 && $pp["action"] == "field") {
                    $txt = "สาขาอุตสาหการ";
                }
                if ($pp["field"] == 6 && $pp["action"] == "field") {
                    $txt = "สาขาสิ่งแวดล้อม";
                }
                if ($pp["field"] == 7 && $pp["action"] == "field") {
                    $txt = "สาขาเคมี";
                }
                if ($pp["field"] == 8 && $pp["action"] == "field") {
                    $txt = "สาขาเหมืองแร่ งานเหมืองแร่";
                }
                if ($pp["field"] == 9 && $pp["action"] == "field") {
                    $txt = "สาขาเหมืองแร่ งานโลหการ";
                }

                $textReplyMessage.= "\n" . $txt. "\nสำหรับคุณ " . $lname . " แล้ว";
            } else {
                $textReplyMessage.=  "ผิดพลาด" . $sql;
            }
        }
        if (!is_null($paramPostback)) {
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
        }
        $replyData = new TextMessageBuilder($textReplyMessage);
    }
    if (!is_null($is_message)) {
        switch ($typeMessage) {
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "p":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่ามาแบบเป็น JSON String โดยใช้คำสั่ง getRawBody() กรณีเป้นข้อความ text
                            $textReplyMessage = $response->getRawBody(); // return string
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;


                    case "สวัสดี":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array
                            $userData = $response->getJSONDecodedBody(); // return array
                            // $userData['userId']
                            // $userData['displayName']
                            // $userData['pictureUrl']
                            // $userData['statusMessage']
                            $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;
                        case "s":
                            // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                            $response = $bot->getProfile($userID);
                            if ($response->isSucceeded()) {
                                // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array
                                $userData = $response->getJSONDecodedBody(); // return array
                                // $userData['userId']
                                // $userData['displayName']
                                // $userData['pictureUrl']
                                // $userData['statusMessage']
                                $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];
                                $textReplyMessage . ' ' . $userData['statusMessage'];
                                $replyData = new TextMessageBuilder($textReplyMessage);
                                break;
                            }
                            // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                            $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                            $replyData = new TextMessageBuilder($failMessage);
                            break;
                    case "n":
                                            // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                                            $response = $bot->getProfile($userID);
                                            if ($response->isSucceeded()) {
                                                // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array
                                                $userData = $response->getJSONDecodedBody(); // return array


                                                $sql = "SELECT * FROM contact where userid = '$userID' ";
                                                $result = mysqli_query($conn, $sql);
                                                if ($result->num_rows > 0) {
                                                    // output data of each row
                                                    if ($row = $result->fetch_assoc()) {
                                                        $name = $row['name'];
                                                        $userID = $row['userid'];
                                                        $profileurl = $row['profilepic'];
                                                        $status = $row['status'];
                                                        $msg = "คุณ " . $name . "\n" . $profileurl . "\n" . $status;
                                                    }
                                                } else {
                                                    $msg = "ไม่พบข้อมูล กรุณาพิมพ์ 'ลงทะเบียน' เพื่อลงทะเบียนก่อน" . $sql;
                                                }

                                                $replyData = new TextMessageBuilder($msg);

                                                break;
                                            }
                                            // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                                            $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                                            $replyData = new TextMessageBuilder($failMessage);
                                            break;


                    case "สมัครใช้งาน":
                              // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                              $response = $bot->getProfile($userID);
                              if ($response->isSucceeded()) {
                                  // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array
                                  $userData = $response->getJSONDecodedBody(); // return array
                                  $userID = $userData['userId'];
                                  $realdisplayname = $userData['displayName'];
                                  $displayname 	= str_replace("'", " ", $realdisplayname);
                                  $profileurl = $userData['pictureUrl'];
                                  $status = $userData['statusMessage'];

                                  $sql = "SELECT MAX(id) AS id FROM contact ";
                                  $result = mysqli_query($conn, $sql);
                                  if ($result->num_rows > 0) {
                                      // output data of each row
                                      if ($row = $result->fetch_assoc()) {
                                          $id = $row[ "id" ] + 1;
                                      }
                                  } else {
                                      $id = 10000;
                                  }
                                  //		$memberid = (isset($_POST["memberid"])) ? $_POST["memberid"] : NULL;

                                  $sql = "INSERT INTO contact (id, userid, name, profilepic, status, updt )
                              VALUES ($id, '$userID','$displayname', '$profileurl', '$status', NOW() ) ";

                                  if (mysqli_query($conn, $sql)) {
                                      //echo $sql . " complete";
                                      //	echo "<script>window.location='pakeypises.php?m=$memberid'</script>";
                                      $replyData = new TextMessageBuilder("สมัครใช้งานเรียบร้อย ขอบคุณครับ");
                                  } else {
                                      $replyData = new TextMessageBuilder($sql);
                                      //  $replyData = new TextMessageBuilder("ลองอีกครั้ง");
                                  }


                                  break;
                              }
                              // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                              $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                              $replyData = new TextMessageBuilder($failMessage);
                            break;

                    case "m":
                      // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                        new MessageTemplateActionBuilder(
                            'สมัครใช้งาน', // ข้อความแสดงในปุ่ม
                            'สมัครใช้งาน'
                        ),
                        new MessageTemplateActionBuilder(
                            'เลือกสาขา',// ข้อความแสดงในปุ่ม
                            'เลือกสาขา'
                        ),
                        new MessageTemplateActionBuilder(
                            'ช่วยเหลือ',// ข้อความแสดงในปุ่ม
                          'help' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),

                          );
                          $imageUrl = 'https://f577fc1c.ngrok.io/me/linebot/pic/logo.png';
                          $replyData = new TemplateMessageBuilder(
                              'Button Template',
                              new ButtonTemplateBuilder(
                                  'COE BOT', // กำหนดหัวเรื่อง
                              'กรุณาเลือกเมนูด้านล่าง', // กำหนดรายละเอียด
                              $imageUrl, // กำหนด url รุปภาพ
                              $actionBuilder  // กำหนด action object
                              )
                            );
                            break;

                    case "เลือกสาขา":
                                  // กำหนด action 4 ปุ่ม 4 ประเภท
                                  $actionBuilder = array(
                                    new PostbackTemplateActionBuilder(
                                        'วิศวกรรมโยธา', // ข้อความแสดงในปุ่ม
                                        http_build_query(array(
                                            'action'=>'field',

                                            'field'=>1
                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                        'สมัครรับข้อมูลสาขาวิศวกรรมโยธา'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                    ),
                                    new PostbackTemplateActionBuilder(
                                        'วิศวกรรมเครืองกล', // ข้อความแสดงในปุ่ม
                                        http_build_query(array(
                                            'action'=>'field',

                                            'field'=>2
                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                        'สมัครรับข้อมูลสาขาวิศวกรรมเครืองกล'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                    ),
                                    new PostbackTemplateActionBuilder(
                                        'วิศวกรรมอุตสาหการ', // ข้อความแสดงในปุ่ม
                                        http_build_query(array(
                                            'action'=>'field',

                                            'field'=>5
                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                        'สมัครรับข้อมูลสาขาอุตสาหการ'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                      ),
                                    new MessageTemplateActionBuilder(
                                        'สาขาอื่น',// ข้อความแสดงในปุ่ม
                                          'สาขาอื่น' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                      ),
                                  );
                                  $imageUrl = 'https://f577fc1c.ngrok.io/me/linebot/pic/logo.png';
                                  $replyData = new TemplateMessageBuilder(
                                      'Button Template',
                                      new ButtonTemplateBuilder(
                                          'สาขา 1/3', // กำหนดหัวเรื่อง
                                              'เลือกสาขาที่ต้องการสมัครรับข้อมูล', // กำหนดรายละเอียด
                                              $imageUrl, // กำหนด url รุปภาพ
                                              $actionBuilder  // กำหนด action object
                                      )
                                  );
                                    break;

                    case "สาขาอื่น":
                                                  // กำหนด action 4 ปุ่ม 4 ประเภท
                                                  $actionBuilder = array(
                                                    new PostbackTemplateActionBuilder(
                                                        'ไฟฟ้า งานไฟฟ้ากำลัง', // ข้อความแสดงในปุ่ม
                                                        http_build_query(array(
                                                            'action'=>'field',
                                                            'field'=>3
                                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                                        'สมัครรับข้อมูลสาขาวิศวกรรมไฟฟ้า งานไฟฟ้ากำลัง'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                                    ),
                                                    new PostbackTemplateActionBuilder(
                                                        'ไฟฟ้า งานไฟฟ้าสื่อสาร', // ข้อความแสดงในปุ่ม
                                                        http_build_query(array(
                                                            'action'=>'field',

                                                            'field'=>4
                                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                                        'สมัครรับข้อมูลสาขาวิศวกรรมไฟฟ้า งานไฟฟ้าสื่อสาร'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                                    ),
                                                    new PostbackTemplateActionBuilder(
                                                        'สิ่งแวดล้อม', // ข้อความแสดงในปุ่ม
                                                        http_build_query(array(
                                                            'action'=>'field',

                                                            'field'=>6
                                                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                                        'สมัครรับข้อมูลสาขาสิ่งแวดล้อม'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                                      ),
                                                    new MessageTemplateActionBuilder(
                                                        'สาขาที่เหลือ',// ข้อความแสดงในปุ่ม
                                                          'สาขาที่เหลือ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                                      ),
                                                  );
                                                  $imageUrl = 'https://f577fc1c.ngrok.io/me/linebot/pic/logo.png';
                                                  $replyData = new TemplateMessageBuilder(
                                                      'Button Template',
                                                      new ButtonTemplateBuilder(
                                                          'สาขา 2/3', // กำหนดหัวเรื่อง
                                                              'เลือกสาขาที่ต้องการสมัครรับข้อมูล', // กำหนดรายละเอียด
                                                              $imageUrl, // กำหนด url รุปภาพ
                                                              $actionBuilder  // กำหนด action object
                                                      )
                                                  );
                                                    break;

                    case "สาขาที่เหลือ":
                      $actionBuilder = array(
                      new PostbackTemplateActionBuilder(
                        'เคมี', // ข้อความแสดงในปุ่ม
                        http_build_query(array(
                        'action'=>'field',
                        'field'=>7
                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                        'สมัครรับข้อมูลสาขาวิศวกรรมเคมี'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                      new PostbackTemplateActionBuilder(
                        'งานเหมืองแร่', // ข้อความแสดงในปุ่ม
                        http_build_query(array(
                        'action'=>'field',
                        'field'=>8
                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                        'สมัครรับข้อมูลสาขาเหมืองแร่ งานเหมืองแร่'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                      new PostbackTemplateActionBuilder(
                        'งานโลหการ', // ข้อความแสดงในปุ่ม
                        http_build_query(array(
                        'action'=>'field',
                        'field'=>9
                        )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                        'สมัครรับข้อมูลสาขาเหมืองแร่ งานโลหการ'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                        new MessageTemplateActionBuilder(
                          'เลือกสาขา',// ข้อความแสดงในปุ่ม
                          'เลือกสาขา' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                          ),
                          );
                                                                                  $imageUrl = 'https://f577fc1c.ngrok.io/me/linebot/pic/logo.png';
                                                                                  $replyData = new TemplateMessageBuilder(
                                                                                      'Button Template',
                                                                                      new ButtonTemplateBuilder(
                                                                                          'สาขา 3/3', // กำหนดหัวเรื่อง
                                                                                              'เลือกสาขาที่ต้องการสมัครรับข้อมูล', // กำหนดรายละเอียด
                                                                                              $imageUrl, // กำหนด url รุปภาพ
                                                                                              $actionBuilder  // กำหนด action object
                                                                                      )
                                                                                  );
                                                                                    break;

                    case "h":
                      $textReplyMessage = "ขอบคุณที่เป็นเพื่อนกับเรา(happy)\n
พิมพ์ m เพื่อแสดงเมนูการใช้งาน\n
พิมพ์ h ช่วยเหลือ\n
พิมพ์ p โพรไฟล์\n
พิมพ์ n ข้อมูลผู้ใช้"
                       ;
                      $replyData = new TextMessageBuilder($textReplyMessage);

                      break;




                    default:
                        $textReplyMessage = "คุณไม่ได้พิมพ์ค่าตามที่กำหนด";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                }
                break;
            case (preg_match('/[image|audio|video]/', $typeMessage) ? true : false):
                $response = $bot->getMessageContent($idMessage);
                if ($response->isSucceeded()) {
                    // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary
                    // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                    $dataBinary = $response->getRawBody(); // return binary
                    // ดึงข้อมูลประเภทของไฟล์ จาก header
                    $fileType = $response->getHeader('Content-Type');
                    switch ($fileType) {
                        case (preg_match('/^image/', $fileType) ? true : false):
                            list($typeFile, $ext) = explode("/", $fileType);
                            $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
                            $fileNameSave = time().".".$ext;
                            break;
                        case (preg_match('/^audio/', $fileType) ? true : false):
                            list($typeFile, $ext) = explode("/", $fileType);
                            $fileNameSave = time().".".$ext;
                            break;
                        case (preg_match('/^video/', $fileType) ? true : false):
                            list($typeFile, $ext) = explode("/", $fileType);
                            $fileNameSave = time().".".$ext;
                            break;
                    }
                    $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                    $botDataUserFolder = $botDataFolder.$userID; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                    if (!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                        mkdir($botDataUserFolder, 0777, true);
                    }
                    // กำหนด path ของไฟล์ที่จะบันทึก
                    $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
                    file_put_contents($fileFullSavePath, $dataBinary); // ทำการบันทึกไฟล์
                    $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
                }
                $failMessage = json_encode($idMessage.' '.$response->getHTTPStatus() . ' ' . $response->getRawBody());
                $replyData = new TextMessageBuilder($failMessage);
                break;
            default:
                $textReplyMessage = json_encode($events);
                $replyData = new TextMessageBuilder($textReplyMessage);
                break;
        }
    }
}
$response = $bot->replyMessage($replyToken, $replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}

// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
