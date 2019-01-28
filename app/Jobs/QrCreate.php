<?php

namespace App\Jobs;

use App\Models\Qr;
use App\Models\Traits\ApiResponseTrait;
use App\Models\Traits\GetQrTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Illuminate\Support\Facades\Log;


class QrCreate implements ShouldQueue
{
    use GetQrTrait, ApiResponseTrait, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $points = 0;
    protected $key;

    //protected $user_id = 0;
    //protected $points = 0;
    //protected $key = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fn, $fs, $fd, $user_id)
    {
        $this->user_id = $user_id;
        try {
            $object = $this->getQrProduct($fn, $fs, $fd);
	$this->points = $this->getPoints($object->products);
        $this->key = $object->id;
            
        } catch (\Exception $e) {
            $this->failed($e);
        }
	
	
        
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 	try {

       	 Qr::create([
            'key' => $this->key,
            'points' => $this->points,
            'user_id' => $this->user_id,
        ]);
	
        } catch (Exception $e) {
		$this->failed($e);
	}	

    }

    public function failed(Exception $exception)
    {
        Log::info($exception);
    }
}
