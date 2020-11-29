<?php

    namespace CodeTech\RouteInterface\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class GetRoutesRequest extends FormRequest
    {
        public function authorize()
        {
            return true;
        }

        public function rules()
        {
            return [];
        }

        public function getSort()
        {
            return $this->input('sort');
        }

        public function getDescending()
        {
            return $this->input('direction') === 'desc';
        }

        public function getSearchTerm()
        {
            return strtolower(preg_replace("/\s+/", "", $this->input('q', '')));
        }

        public function getSearchColumn()
        {
            return $this->input('searchColumn');
        }
    }
