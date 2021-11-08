<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;
use \Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get()
    {
        $data = DB::table('departments')
            ->select(DB::raw('departments.name as name, count(members.id) as count_members, MAX(members.salary) as max_salary'))
            ->leftJoin('departments_members', 'departments_members.department_id', '=', 'departments.id')
            ->leftJoin('members', 'departments_members.member_id', '=', 'members.id')
            ->groupBy('departments.id')
            ->paginate(10);

        return response()->json(($data));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Department::validate());

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::table('departments')->insert([
            'name' => $request->name,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json('Отдел успешно добавлен.');
    }

    /**
     * @param Integer $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Integer $id, Request $request)
    {
        $validator = Validator::make($request->all(), Department::validate());

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::table('departments')
            ->where(['id' => $id])
            ->update([
                'name' => $request->name,
            ]);

        return response()->json('Отдел успешно добавлен.');
    }

    /**
     * @param Integer $id
     * @return JsonResponse
     */
    public function delete(Integer $id)
    {
        $checkMembers =  DB::table('departments')
            ->select(DB::raw('count(members.id) as count_members'))
            ->where(['departments.id' => $id])
            ->leftJoin('departments_members', 'departments_members.department_id', '=', 'departments.id')
            ->leftJoin('members', 'departments_members.member_id', '=', 'members.id')
            ->groupBy('departments.id');

        if ($checkMembers->first()->count_members > 0) {
            return response()->json('Удалить отдел запрещено, так как в нём имеются сотрудники');
        }

        $checkMembers->delete();

        return response()->json('Отдел успешно удалён.');
    }
}
