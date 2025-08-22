<?php
  namespace App\Helpers;

use App\Models\LogActivitiesModel;
use Request;

  class LogActivity
  {
      public static function addToLog($subject)
      {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
        LogActivitiesModel::create($log);
      }


      public static function logActivityLists()
      {
        return LogActivitiesModel::latest()->get();
      }


  }
