<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function destroy($id)
{
    $notif = Notification::findOrFail($id);
    Notification::where('ID',$id)->delete();

    return redirect()->back();
}
}
