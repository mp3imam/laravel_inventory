<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class getSatker extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
   /**
 * @OAS\Schema(type="object")
 */
    public function toArray($request)
    {
        /**
     * @OAS\Property(property="id",type="integer")
     * @OAS\Property(property="kode_satker",type="string")
     * @OAS\Property(property="name",type="string")
     * @OAS\Property(property="address",type="string")
     *
     * @return array
     */
        return [
            'id' => $this->id,
            'kode_satker' => $this->kode_satker,
            'name' => $this->name,
            'address' => $this->address,

        ];
        // return parent::toArray($request);
    }
}
