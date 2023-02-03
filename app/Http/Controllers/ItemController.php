<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Item;
use App\Allergy;
use App\ItemAllergy;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $file = $request->file('file');
        $data = Excel::load($file)->get();
        $errors = array();

        foreach ($data as $key => $value) {
            $validator = Validator::make($value->toArray(), [
                'sku' => 'required|string',
                'position' => 'required|integer',
                'main' => ['required', Rule::in([1, 0])],
                'name' => 'required|string',
                'allergies' => 'nullable|string',
                'price' => 'required|integer',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                $errors[] = $validator->errors();
            } else {
                $item = new Item;
                $item->sku = $value->sku;
                $item->position = $value->position;
                $item->main = $value->main;
                $item->name = $value->name;
                $item->price = $value->price;
                $item->status = $value->status;
                $item->save();

                if ($value->allergies) {
                    $allergies = explode(',', $value->allergies);
                    foreach ($allergies as $allergy) {
                        $allergyCheck = Allergy::where('name', $allergy)->first();
                        if (!$allergyCheck) {
                            $newAllergy = new Allergy;
                            $newAllergy->name = $allergy;
                            $newAllergy->save();
                            $allergyCheck = $newAllergy;
                        }
                        $itemAllergy = new ItemAllergy;
                        $itemAllergy->item_id = $item->id;
                        $itemAllergy->allergy_id = $allergyCheck->id;
                        $itemAllergy->save();
                        //
                    }
                }
            }
        }
    }

}
