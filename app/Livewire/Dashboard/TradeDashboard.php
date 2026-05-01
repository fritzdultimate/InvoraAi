<?php

namespace App\Livewire\Dashboard;
use App\Models\Trade;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class TradeDashboard extends Component {

    public $trades;
    public $fundingRates = [];
    public $loading = false;
    public $error = null;

    protected $listeners = ['refreshTrades' => '$refresh'];

    public function loadTrades() {
        $this->trades = Trade::with('asset')
            ->latest()
            ->take(20)
            ->get();
    }

    public function fetchFundingRates() {
        $this->loading = true;
        $this->error = null;

        try {
            $response = Http::withHeaders([
                'api_key' => config('services.coinalyze.key'),
            ])->get('https://api.coinalyze.net/v1/funding-rate', [
                'symbols' => 'ETHUSDT_PERP.A,ETHUSD_PERP.0'
            ]);

            if ($response->successful()) {

                $data = collect($response->json());

                $this->fundingRates = $response->json();
            } else {
                $this->error = 'API Error: ' . $response->body();

                dd($this->error);
            }

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        $this->loading = false;
    }

    public function mount(){
        $this->loadTrades();
    }

    public function render() {
        $this->loadTrades();
        return view('livewire.dashboard.trade-dashboard');
    }

}
