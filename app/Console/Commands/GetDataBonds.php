<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;


use App\Bond;
use App\User;
use App\BondDetail;


use HeadlessChromium\BrowserFactory;
use Sunra\PhpSimple\HtmlDomParser;

define('MAX_FILE_SIZE', 6000000);

class GetDataBonds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get-bonds-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Detail Data bondd';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = 100;
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $trash_ids = $user->trashBond->pluck('id')->toArray();
        $data_success_ids = BondDetail::all()->pluck('bond_id')->toArray();
        $bonds = Bond::where('faceValue', '!=', 0)
            ->whereNotIn('id', $trash_ids)
            ->whereNotIn('id', $data_success_ids)
            ->orderBy('updated_at')
            ->take($limit)->get();

        foreach ($bonds as $bond_item) {
            try {
                $time = Carbon::now();

                $current_yield = 0;
                $maturity_yield = 0;
                $maturity_date = $time->toDateTimeString();
                $maturity_calloption = 0;
                $date_calloption = $time->toDateTimeString();
                $paymant_date = $time->toDateTimeString();
                $date_payment_coupon = $time->toDateTimeString();
                $accumulated_coupon = 0;
                $amount_coupon = 0;
                $nominal = 0;
                $period_payment = 0;

                $browserFactory = new BrowserFactory('C:\Program Files (x86)\Google\Chrome\Application\chrome.exe');
                //$browserFactory = new BrowserFactory('google-chrome');
                // starts headless chrome
                $browser = $browserFactory->createBrowser();
                $page = $browser->createPage();
                echo "https://www.tinkoff.ru/invest/bonds/$bond_item->ticker/\n";
                $page->navigate("https://www.tinkoff.ru/invest/bonds/$bond_item->ticker/")->waitForNavigation();
                $html = $page->getHtml();
                $htmlDom = HtmlDomParser::str_get_html($html);
                if (is_bool($htmlDom)) {
                    echo "Пропуск\n";
                    continue;
                }

                $isAccess = $htmlDom->find(".//*[@data-qa-file='ProductError']");
                if (!empty($isAccess)) {
                    $detail_empty_bond = BondDetail::create([
                        'bond_id' => $bond_item->id,
                        'current_yield' => $current_yield,
                        'maturity_yield' => $maturity_yield,
                        'maturity_calloption' => $maturity_calloption,
                        'maturity_date' => $maturity_date,
                        'date_calloption' => $date_calloption,
                        'date_payment_coupon' => $date_payment_coupon,
                        'paymant_date' => $paymant_date,
                        'accumulated_coupon' => $accumulated_coupon,
                        'amount_coupon' => $amount_coupon,
                        'nominal' => $nominal,
                        'period_payment' => $period_payment,
                    ]);
                    continue;
                }
                $list_row = $htmlDom->find(".//*[@data-qa-file='DetailsTable']");

                foreach ($list_row as $item) {
                    $count = 0;
                    $header_list = $item->find(".//*[@data-qa-file='DetailsTable']/div/div[@role='rowheader']");
                    foreach ($header_list as $item_header) {
                        $cell = $item->find(".//*[@data-qa-file='DetailsTable']/div/div[@role='cell']", $count);
                        $header_text = str_replace("&nbsp;", '', $item_header->plaintext);
                        $header_text = trim($header_text);
                        $cell_value = str_replace(',', '.', $cell->plaintext);
                        if ($header_text == 'Текущая доходность') {
                            $current_yield = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Доходность к погашению') {
                            $maturity_yield = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Дата погашения облигации') {
                            $maturity_date = $cell_value;
                        }
                        if ($header_text == 'Доходность к колл-опциону') {
                            $maturity_calloption = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Дата колл-опциона') {
                            $date_calloption = $cell_value;
                        }
                        if ($header_text == 'Дата выплаты купона') {
                            $paymant_date = $cell_value;
                        }
                        if ($header_text == 'Дата выплаты купона') {
                            $date_payment_coupon = $cell_value;
                        }
                        if ($header_text == 'Накопленный купонный доход') {
                            $accumulated_coupon = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Величина купона') {
                            $amount_coupon = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Номинал') {
                            $nominal = preg_replace("/[^0-9.]/", "", $cell_value);
                        }
                        if ($header_text == 'Периодичность выплаты купона') {
                            $period_payment = $cell_value;
                        }

                        $count++;
                    }
                    
                }

            } finally {
                $browser->close();
            }

            echo $current_yield . "\n";
            
            $detail_empty_bond = BondDetail::create([
                'bond_id' => $bond_item->id,
                'current_yield' => round($current_yield, 2),
                'maturity_yield' => round($maturity_yield, 2),
                'maturity_calloption' => round($maturity_calloption, 2),
                'maturity_date' => Carbon::parse($maturity_date),
                'date_calloption' => $date_calloption,
                'date_payment_coupon' => $date_payment_coupon,
                'paymant_date' => $paymant_date,
                'accumulated_coupon' => round($accumulated_coupon, 2),
                'amount_coupon' => round($amount_coupon, 2),
                'nominal' => $nominal,
                'period_payment' => $period_payment,
            ]);
        }
    }
}
