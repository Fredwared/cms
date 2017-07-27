<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Globals;
use Illuminate\Http\Request;

class LogController extends BackendController
{
    public function index(Request $request)
    {
        $log_type = $request->log_type ?? null;
        $model_name = $request->model_name ?? null;
        $model_id = $request->model_id ?? null;
        $log_ip = $request->log_ip ?? null;
        $user_id = $request->user_id ?? [];
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessLog = Globals::getBusiness('Log');
        $businessUser = Globals::getBusiness('User');

        $params = [
            'log_type' => $log_type,
            'user_id' => $user_id,
            'model_name' => $model_name,
            'model_id' => $model_id,
            'log_ip' => $log_ip,
            'user_id' => $user_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $arrListLog = $businessLog->getListLog($params);

        if ($arrListLog->total() > 0) {
            $maxPage = ceil($arrListLog->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.log.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListLog->appends($params)->links();
        
        //get user info
        $arrUser = $businessUser->findByMany($user_id);

        return view('backend.log.index', compact('arrListLog', 'pagination', 'item', 'log_type', 'user_id', 'model_name', 'model_id', 'log_ip', 'arrUser', 'date_from', 'date_to'));
    }

    public function queryLog(Request $request)
    {
        $log_url = $request->log_url ?? null;
        $log_ip = $request->log_ip ?? null;
        $log_method = $request->log_method ?? null;
        $user_id = $request->user_id ?? [];
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $item = check_paging($request->item);
        $page = $request->page ?? 1;

        //get model
        $businessLog = Globals::getBusiness('Log');
        $businessUser = Globals::getBusiness('User');

        $params = [
            'log_url' => $log_url,
            'log_ip' => $log_ip,
            'log_method' => $log_method,
            'user_id' => $user_id,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $arrListLog = $businessLog->getListLogQuery($params);

        if ($arrListLog->total() > 0) {
            $maxPage = ceil($arrListLog->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.log.query', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListLog->appends($params)->links();

        //get user info
        $arrUser = $businessUser->findByMany($user_id);

        return view('backend.log.query', compact('arrListLog', 'arrUser', 'pagination', 'item', 'log_url', 'log_ip', 'log_method', 'user_id', 'date_from', 'date_to'));
    }

    public function destroy(Request $request, $days = 60, $type = null)
    {
        $businessLog = Globals::getBusiness('Log');
        $businessLog->clearLog($days, $type);

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.log.deleted'));
            return redirect(route('backend.log.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.log.deleted')]);
        }
    }
}
