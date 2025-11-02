<template>
    <div>
        <div class="row">
            <div class="col-md-6 text-right mb-sm">
                <span v-if="inputs.length">Total Items type: {{inputs.length}} and Total Items: {{stock}}
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 text-center mb-sm">Weekend 
            </div>
            <div class="col-md-3 text-center mb-sm">Govt. holiday
            </div>
            
        </div>

        <ul id="stock-add">
          <li v-for="(input, index) in inputs" class="mb-sm">
             <input v-if="input.id" class="hidden" :name="'details['+index+'][id]'" :value="input.id"></input>
            <div class="row">
                <div class="col-md-3">
                     <select class="form-control" :name="'details['+index+'][weekend_date]'" v-model="input.weekend_date" @change="getProductParticulars($event)" required="required">
                        <option v-if="input.weekend_date==null" value="null">Select Product</option>
                        <option v-else value="">Select Product</option>
                        <option v-for="(val,key) in products" v-bind:value="key">
                            {{ val }}
                        </option>
                     </select>

                </div>
                <div class="col-md-3">
                     <select class="form-control"
                       :name="'details['+index+'][govt_holiday_date]'" v-model="input.govt_holiday_date">
                        <option value="">Select For Whoom</option>
                        <option v-for="(val,key) in particulars" v-bind:value="key">
                            {{ val }}
                        </option>
                     </select>
                </div>
                <div class="col-md-2">
                    <button v-if="index" class="btn btn-danger" @click="deleteRow(index,$event);">Delete</button>
                </div>
            </div>
          </li>
        </ul>
        <div class="row">
            <div class="col-md-offset-6 col-md-2">
                 <button class="btn btn-success" @click="addRow">Add More</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
         data() {
            return {
                inputs:[{
                    brand_id: '',
                    size_id: '',
                    qty: '',
                    unit_price: ''
                }],
                stock:0
            }
        },
        mounted() {
            if(this.details.length){
                this.inputs=this.details;
                this.countTotal();
            }
        },
         props: {
           brands: Object,
           sizes: Object,
           details: Array
        },
        methods: {
            addRow(event) {
              event.preventDefault();
              this.inputs.push({
                    brand_id: '',
                    size_id: '',
                    qty: '',
                    unit_price: ''
                });
            },

            countTotal(){
                let add=0;
               this.inputs.forEach(function (value, key) {
                    if (!isNaN(parseFloat(value.qty)) && isFinite(value.qty)) {
                        add += parseInt(value.qty);
                    }
               });
               this.stock=add;
            },

            checkIsDuplicateBrandAndSize(index,flag) {
                let result = this.inputs.filter((element,k) => {
                    if(k !=index && element.brand_id !='') {
                        return element;
                    }
                });
                let found = result.find((element)=> {
                    return  (element.brand_id == this.inputs[index].brand_id);
                });
                if (found) {
                    if (flag == 'brand') {
                        this.inputs[index].brand_id = '';
                    } 
                    alert("This Employee Reporting Sequence Already Created... !, Please select another Employee.");
                }
            },

            deleteRow(index,event) {
                event.preventDefault();
                this.inputs.splice(index,1);
                this.countTotal();
            }
        }
}
</script>
<style>
    #stock-add{
        list-style: none;
    }
</style>
