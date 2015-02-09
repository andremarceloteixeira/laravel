<?php

class HomeController extends BaseController {

    protected $user;
    
    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('ajax', ['only' => ['expertsProcesses']]);
        $this->user = Auth::user();
    }

    public function index() {
        if(Check::isAdmin()) {
            $processes = Process::where('status_id','=', 2)->get();
            foreach($processes as $k => $p) {
                if(!Check::isProcessLate($p)) {
                    $processes->forget($k);
                }
            }
            return View::make('dashboard.index')
                    ->with(['pending' => Process::pending()->count(), 'processing' => Process::processing()->count(), 'completed' => Process::completed()->count(), 'cancelled' => Process::cancelled()->count(), 'processes_deadlines' => $processes]);
        } else if(Check::isExpert()) {
            return Redirect::route('experts.processes.index');
        } else if(Check::isClient()) {
            return Redirect::route('clients.processes.index');
        }
    }
    
    public function expertsProcesses() {
        $experts = Expert::all();
        if(count($experts) < 2) {
            return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.chart_error')]);
        }
        $data = [];
        $data['status'] = 'success';
        $labels = [];
        $arr = [];
        $arr['label'] = 'Processos em Processamento';
        $arr['fillColor'] = 'rgba(91,192,222,0.2)';
        $arr['strokeColor'] = 'rgba(91,192,222,1)';
        $arr['pointColor'] = 'rgba(91,192,222,1)';
        $arr['pointStrokeColor'] = '#fff';
        $arr['pointHighlightFill'] = '#fff';
        $arr['pointHighlightStroke'] = 'rgba(220,220,220,1)';
        
        $arr2 = [];
        $arr2['label'] = 'Processos Completos';
        $arr2['fillColor'] = 'rgba(61,148,0,0.2)';
        $arr2['strokeColor'] = 'rgba(61,148,0,1)';
        $arr2['pointColor'] = 'rgba(61,148,0,1)';
        $arr2['pointStrokeColor'] = '#fff';
        $arr2['pointHighlightFill'] = '#fff';
        $arr2['pointHighlightStroke'] = 'rgba(151,187,205,1)';
        
        $arr3 = [];
        $arr3['label'] = 'Processos Cancelados';
        $arr3['fillColor'] = 'rgba(200,58,42,0.2)';
        $arr3['strokeColor'] = 'rgba(200,58,42,1)';
        $arr3['pointColor'] = 'rgba(200,58,42,1)';
        $arr3['pointStrokeColor'] = '#fff';
        $arr3['pointHighlightFill'] = '#fff';
        $arr3['pointHighlightStroke'] = 'rgba(151,187,205,1)';
        
        foreach($experts as $e) {
            $labels[] = $e->name;
            $arr['data'][] = $e->processes()->where('status_id','=',2)->count();
            $arr2['data'][] = $e->processes()->where('status_id','=',3)->count();
            $arr3['data'][] = $e->processes()->where('status_id','=',4)->count();
        }
        $data['data'] = ['labels' => $labels, 'datasets' => [$arr, $arr3, $arr2]];
        
        return Response::json($data);
    }
    
    public function changeLanguage($token) {
        if (File::isDirectory(app_path() . "/lang/" . $token)) {
            App::setLocale($token);
            $cookie = Cookie::make('lang', $token);
            return Redirect::back()->withCookie($cookie);
        }
    }

}
