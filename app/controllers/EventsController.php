<?php

class EventsController extends BaseController {

    protected $user;
    protected $events;

    public function __construct(CalendarEvent $event) {
        $this->beforeFilter('auth');
        $this->beforeFilter('feature:calendar');
        $this->user = Auth::user();
        $this->events = $event;
    }

    public function index() {
        $events = CalendarEvent::where(DB::raw('DATE(starts)'), '=', Carbon::now()->format('Y-m-d'))->where('user_id', '=', $this->user->id)->get();
        
        $today = "";
        foreach($events as $e) {
            $today .= '<p><b>'.trans('events.name').': </b>' . $e->name.' <span class="label '.$e->type->label.'" style="padding-left: 15px;">'.$e->type->name.'</span><br><b>'.trans('events.description').': </b>'.$e->description.'</p>';
        }

        
        return View::make('calendar.index')
                ->with(['today' => $today]);
    }

    public function store() {
        $rules = ['name' => 'required|max:80', 'description' => 'max:200', 'type_id' => 'required|exists:event_priorities,label', 'starts' => 'required', 'ends' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $input = Input::all();
            $input['starts'] = date('Y-m-d G:i:s', ($input['starts'] / 1000));
            $input['ends'] = date('Y-m-d G:i:s', ($input['ends'] / 1000));
            $input['user_id'] = $this->user->id;
            $input['type_id'] = EventType::where('label', '=', $input['type_id'])->get()[0]->id;
            $event = $this->events->create($input);

            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('events.create'), 'id' => $event->id]);
        }
        return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('events.error')]);
    }

    public function update() {
        $rules = ['name' => 'required|max:80', 'description' => 'max:200', 'type_id' => 'required|exists:event_priorities,label', 'id' => 'required|exists:events,id'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $input = Input::all();
            $input['user_id'] = $this->user->id;
            $input['type_id'] = EventType::where('label', '=', $input['type_id'])->get()[0]->id;
            $event = $this->events->find($input['id']);
            $event->update($input);
            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('events.edit')]);
        }
        return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('events.error')]);
    }

    public function destroy() {
        $rules = ['id' => 'required|exists:events,id'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $this->events->find(Input::get('id'))->delete();
            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('events.delete')]);
        }
        return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('events.error')]);
    }
    
    public function move() {
        $rules = ['id' => 'required|exists:events,id', 'starts' => 'required', 'ends' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $event = $this->events->find(Input::get('id'));
            $event->starts = date('Y-m-d G:i:s', (Input::get('starts') / 1000));
            $event->ends = date('Y-m-d G:i:s', (Input::get('ends') / 1000));
            $event->save();
            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('events.moved')]);
        }
        return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('events.error')]);
    }

}
