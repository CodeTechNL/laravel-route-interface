<template>
    <div>
        <div class="row">
            <div class="col-md-6">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="control-label col-md-3">Search Term</label>
                                    <div class="col-sm-4">
                                        <input type="text" v-model="options.q" @keyup="searchOrSortChanged"
                                               class="form-control"
                                               placeholder="Search term...">
                                    </div>
                                    <label class="control-label col-md-2">Column</label>
                                    <div class="col-sm-3">
                                        <select class="form-control" v-model="options.searchColumn"
                                                @change="searchOrSortChanged">
                                            <option v-for="field in fields"
                                                    v-text="field.label"
                                                    :value="field.key"></option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <label class="control-label col-md-3">Sort</label>
                                    <div class="col-sm-9 mb-4">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select class="form-control" v-model="options.sort"
                                                        @change="searchOrSortChanged">
                                                    <option v-for="field in fields"
                                                            v-text="field.label"
                                                            :value="field.key"></option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" v-model="options.direction"
                                                        @change="searchOrSortChanged">
                                                    <option value="asc">ASC</option>
                                                    <option value="desc">DESC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Display fields</h6>
                    </div>
                    <div class="card-body">
                        <ul class="field-display">
                            <li v-for="field in fields">
                                <label>
                                    <input type="checkbox" v-model="options.displayFields" :value="field.key">
                                    <span v-text="field.label"></span>
                                </label>
                            </li>
                        </ul>

                    </div>

                </div>
            </div>
        </div>


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Route List</h6>
            </div>
            <div class="card-body">

                <table class="table table-striped table-hover table-responsive">
                    <thead>
                    <tr>
                        <th width="10%" v-for="field in fields" v-if="showField(field)" v-text="field.label">Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="route in data">

                        <template v-for="field in fields" v-if="field.type === 'array'">
                            <td v-if="showField(field)">

                                <ul v-if="field.key === 'wheres'" class="no-style">
                                    <li v-for="(key,val) in route[field.key]">
                                        <span v-text="val+':'" class="strong label"></span>
                                        <span v-text="key"></span>
                                    </li>
                                </ul>
                                <ul v-else class="no-style">
                                    <li v-for="val in route[field.key]" v-text="val"></li>
                                </ul>
                            </td>
                        </template>
                        <template v-else class="no-style">
                            <td v-if="showField(field)" v-text="route[field.key]"></td>
                        </template>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            fields: {
                default() {
                    return [
                        {
                            key: 'name',
                            label: 'Name'
                        },
                        {
                            key: 'domain',
                            label: 'Domain'
                        },
                        {
                            key: 'action_method',
                            label: 'Action Method'
                        },
                        {
                            key: 'uri',
                            label: 'URI'
                        },
                        {
                            key: 'middleware',
                            label: 'Middleware',
                            type: 'array'
                        },
                        {
                            key: 'controller',
                            label: 'Controller'
                        },
                        {
                            key: 'prefix',
                            label: 'Prefix'
                        },
                        {
                            key: 'wheres',
                            label: 'Wheres',
                            type: 'array'
                        },
                        {
                            key: 'route_parameters',
                            label: 'Route Parameters',
                            type: 'array'
                        },
                    ];
                }
            }
        },
        watch: {
            'options.displayFields'(){
                this.setLocalStorage();
            },
        },
        data() {
            return {
                data: [],

                timeout: null,
                options: {
                    sort: 'uri',
                    q: '',
                    direction: 'asc',
                    displayFields: [],
                    searchColumn: false,
                },
            }
        },
        methods: {
            searchOrSortChanged() {
                this.getData();
            },
            getData() {
                let self = this;

                clearTimeout(self.timeout);

                self.timeout = setTimeout(function () {
                    axios.get(window.location.origin + window.location.pathname + '.json', {
                        params: self.options
                    })
                        .then(response => {
                            self.data = response.data;
                            self.setLocalStorage();
                        })
                }, 500);
            },
            setLocalStorage() {
                localStorage.setItem('options', JSON.stringify(this.options));
            },
            showField(field) {
                return this.options.displayFields.includes(field.key);
            },
            restoreLocalStorage() {
                let self = this;
                if (localStorage.getItem('options')) {
                    self.options = JSON.parse(localStorage.getItem('options'));
                }
            }
        },
        mounted() {
            this.getData();
            let self = this;
            _.each(this.fields, field => {
                self.options.displayFields.push(field.key)
            });

            self.options.searchColumn = self.fields[0].key;

            this.restoreLocalStorage();
        }
    }
</script>

<style>
    .field-display {
        column-count: 3;
        list-style: none;
        margin: 0;
        padding: 0;
    }
</style>
