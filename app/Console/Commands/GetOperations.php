<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Operation;

use \jamesRUS52\TinkoffInvest\TIClient;
use \jamesRUS52\TinkoffInvest\TISiteEnum;
use \jamesRUS52\TinkoffInvest\TICurrencyEnum;
use \jamesRUS52\TinkoffInvest\TIInstrument;
use \jamesRUS52\TinkoffInvest\TIPortfolio;
use \jamesRUS52\TinkoffInvest\TIOperationEnum;
use \jamesRUS52\TinkoffInvest\TIIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandleIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandle;
use \jamesRUS52\TinkoffInvest\TIOrderBook;
use \jamesRUS52\TinkoffInvest\TIInstrumentInfo;

class GetOperations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get-operations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $from = new \DateTime();
        $from->sub(new \DateInterval("P7D"));
        $to = new \DateTime();
        $operations = $client->getOperations($from, $to);

        foreach ($operations as $operation) {
            
            $model = Operation::firstOrCreate(
                ['operation_id' => $operation->getId()],
                [
                    'operation_id' => $operation->getId(),
                    'status' => $operation->getStatus() ? $operation->getStatus() : 0,
                    'figi' => $operation->getFigi() ? $operation->getFigi() : 0,
                    'payment' => $operation->getPayment() ? $operation->getPayment() : 0,
                    'price' => $operation->getPrice() ? $operation->getPrice() : 0,
                    'commission' => 0,
                    'currency' => $operation->getCurrency() ? $operation->getCurrency() : 0,
                    'instrumentType' => $operation->getInstrumentType() ? $operation->getInstrumentType() : 0,
                    'date' => $operation->getDate() ? $operation->getDate() : 0,
                    'operationType' => $operation->getOperationType() ? $operation->getOperationType() : 0,
                ]
            );
           
        }
    }
}
