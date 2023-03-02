<?php

namespace app\commands;

use app\models\Events;
use app\models\Saver;
use GuzzleHttp\Client;
use yii\console\Controller;

class SaleController extends Controller
{
    public function actionAuth()
    {
        $client = new Client();
        $url = 'https://cabinet.cultureticket.uz/api/CultureTicket/Token';

        $res = $client->request('POST', $url, [
            'json' => [
                "login" => 'umar@iticket.uz',
                "password" => '123456'
            ],
            'verify' => false
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        $path = dirname(__DIR__, 1) . '/web/data/auth';

        if (isset($json['result']['accessToken'])){
            file_put_contents($path, $json['result']['accessToken']);
        }
        var_dump($json);
    }

    public function getToken()
    {
        $token = file_get_contents(dirname(__DIR__, 1) . '/web/data/auth');
        return $token;
    }

    public function getResponse($url) {
        $client = new Client();
        $res = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "Bearer " . $this->getToken(),
            ],
            'verify' => false
        ]);

        return $res;
    }

//  Crontab
//  */1 * * * * /usr/bin/php /home/www/ticket-mon/yii hello/test >> /home/www/ticket-mon/test.log
    public function actionSold()
    {
        $events = Events::find()->all();
        foreach ($events as $event) {
            if ($event->session_id && ($event->is_active === 1)) {
                $url1 ='https://cabinet.cultureticket.uz/api/CultureTicket/SessionTickets/' . $event->session_id;
                $res = $this->getResponse($url1);
                $tickets = json_decode($res->getBody()->getContents(), true);

                $url2 = 'https://cabinet.cultureticket.uz/api/CultureTicket/PalaceHallSeats/' . $event->hall;
                $res2 = $this->getResponse($url2);
                $seats = json_decode($res2->getBody()->getContents(), true);

                $soldTickets = [];
                $rejectedTickets = [];
                $newTickets = [];
                $invitationTickets = [];
                foreach ($tickets['result'] as $ticket) {
                    if(($ticket['ticketStatusName'] === "Проданный") && ($ticket['tarifName'] !== "Пригласительное место")) {
                        array_push($soldTickets, $ticket);
                    }
                    if(($ticket['ticketStatusName'] === "Проданный") && ($ticket['tarifName'] === "Пригласительное место")) {
                        array_push($invitationTickets, $ticket);
                    }
                    if($ticket['ticketStatusName'] === "Возвратный") {
                        array_push($rejectedTickets, $ticket);
                    }
                    if($ticket['ticketStatusName'] === "Новый") {
                        array_push($newTickets, $ticket);
                    }
                }

                $soldSeats = [];
                $rejectedSeats = [];
                $newSeats = [];
                $invitationSeats = [];
                foreach ($soldTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($soldSeats, $seat);
                        }
                    }
                }
                foreach ($rejectedTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($rejectedSeats, $seat);
                        }
                    }
                }

                foreach ($newTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($newSeats, $seat);
                        }
                    }
                }
                foreach ($invitationTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($invitationSeats, $seat);
                        }
                    }
                }

                if (!empty($newSeats)) {
                    foreach ($newSeats as $newSeat) {
                        $seat = Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $newSeat['svgSeatId']])->one();
                        if ($seat){
                            $seat->comment = "На продаже";
                            $seat->color = "C694C3";
                            $seat->save(false);
                        } else {
                            $model = new Saver();
                            $model->event_id = $event->id;
                            $model->seat_id = 'seat-' . $newSeat['svgSeatId'];
                            $model->place_title = 'Sector: ' . $newSeat['sectorName'] . ' Row: ' . $newSeat['rowNumber'] . ' Seat: ' . $newSeat['seatNumber'];
                            $model->comment = 'На продаже';
                            $model->color = 'C694C3';
                            $model->save(false);
                        }
                    }
                }

                if (!empty($soldSeats)){
                    foreach ($soldSeats as $soldSeat) {
                        $seat = Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $soldSeat['svgSeatId']])->one();

                        if ($seat) {
                            $seat->comment = 'Проданное место';
                            $seat->color = 'CCCCCC';
                            $seat->save(false);
                        } else {
                            $model = new Saver();
                            $model->event_id = $event->id;
                            $model->seat_id = 'seat-' . $soldSeat['svgSeatId'];
                            $model->place_title = 'Sector: ' . $soldSeat['sectorName'] . ' Row: ' . $soldSeat['rowNumber'] . ' Seat: ' . $soldSeat['seatNumber'];
                            $model->comment = 'Проданное место';
                            $model->color = 'CCCCCC';
                            $model->save(false);
                        }
                    }
                }
                if (!empty($rejectedSeats)) {
                    foreach ($rejectedSeats as $rejectedSeat) {
                        if (Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()){
                            Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()->delete();
                        }
                    }
                }

                if (!empty($invitationSeats)) {
//                    var_dump($invitationSeats);die();
                    foreach ($invitationSeats as $invitationSeat) {
                        $seat = Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $invitationSeat['svgSeatId']])->one();
                        if ($seat)
                        {
                            if ($seat->comment === 'На продаже'){
                                $seat->delete();
                            }
                        }

                    }
                }

            }
        }
        echo  "Tickets synchronized. Execution time: " . \Yii::getLogger()->getElapsedTime() . "sec. - " . date("d-m-Y H:i:s") .  PHP_EOL;
    }
}