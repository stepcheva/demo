<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch ($request->param) {
            case 'new':
                $items = Contact::isNotAnswered()->latest()->paginate(20);
                break;
            case 'answered':
                $items = Contact::isAnswered()->latest()->paginate(20);
                break;
        }

       return view('admin.contacts.list', ['items' => $items, 'param' => $request->param]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id, Request $request)
    {
        Contact::destroy($id);
        session()->flash('success', 'Обращение успешно удалено.');
        return redirect()->route('contacts.index', ['param' => $request->param]);
    }

    public function answered(Contact $contact, Request $request)
    {
        $contact->update([
            'is_answered' => 1,
        ]);
        session()->flash('success', 'Ответ на обращение отправлен.');
        return  redirect()->route('contacts.index', ['param' => $request->param]);
    }

}


