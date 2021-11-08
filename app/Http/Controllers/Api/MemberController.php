<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

class MemberController extends Controller
{

    /**
     * @return  JsonResponse
     */
    public function get(): JsonResponse
    {
        $data = DB::table('members')
            ->select(DB::raw('concat(name, " ", surname, " ", patronymic) as full_name, sex, salary'))
            ->paginate(10);

        return response()->json([MemberResource::collection($data)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), Member::validate());

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $memberId = DB::table('members')->insertGetId([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'sex' => $request->sex,
            'salary' => $request->salary,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        foreach ($request->departments as $item) {
            DB::table('members_departments_relations')->insert([
                'member_id' => $memberId,
                'department_id' => $item,
            ]);
        }

        return response()->json('Сотрудник успешно добавлен.');
    }

    /**
     * @param Integer $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Integer $id, Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'required',
            'salary' => 'integer|required',
            'departments' => 'array|required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        DB::table('members')->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'sex' => $request->sex,
            'salary' => $request->salary,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $currentRelations = DB::table('members_departments_relations')
            ->where(['member_id' => $id])
            ->pluck('department_id')
            ->toArray();
        $newRelations = $request->departments;

        foreach ($newRelations as $idRelations => $item) {
            if (in_array($item, $currentRelations)) {
                unset($currentRelations[$idRelations]);
            } else {
                DB::table('members_departments_relations')->insert([
                    'member_id' => $id,
                    'department_id' => $item,
                ]);
            }
        }

        DB::table('members_departments_relations')
            ->where(['member_id' => $id])
            ->whereIn('department_id', $currentRelations)
            ->delete();

        return response()->json('Отдел успешно обновлён.');
    }

    /**
     * @param Integer $id
     * @return JsonResponse
     */
    public function delete(Integer $id): JsonResponse {
        DB::table('members')
            ->where(['id' => $id])
            ->delete();

        return response()->json('Сотрудник успешно удалён.');
    }
}
