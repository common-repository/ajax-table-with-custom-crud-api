( function() {
    //alert(1);
    //jQuery('#home_page_banner').html(<?php echo do_shortcode('[banner id="6135"]'); ?>);
    var apiName = 'sha4';
    var dataValues = [];
    const fetchItems = () => {
                this.isBusy = true;
                var url = window.location.href.split("wp-admin")[0] + 'wp-json/ajdt/v1/' + 'sha4';
                fetch(url).then((response) => {
                    //console.log(response);
                    this.isBusy = false;
                    return response.json()
                }).then((data)=>{
                    //console.log(data);
                    this.dataValues = data;
                });
            };


    const Foo = { template: '<div>foo</div>' }
    const Bar = { template: '<div>bar</div>' }
    //        el: document.querySelector('#mount_'+apiName),
    const dataGridItems = {
        template:`<div><h4>Item List</h4>
                        <div class="container">
                            <b-table striped hover :items="dataValues">
                            <template v-slot:cell(name)="row">
                                {{ row.value.first }} {{ row.value.last }}
                            </template>

                            <template v-slot:cell(actions)="row">
                                <b-button size="sm" @click="info(row.item, row.index, $event.target)" class="mr-1">
                                Info modal
                                </b-button>
                                <b-button size="sm" @click="row.toggleDetails">
                                {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
                                </b-button>
                            </template>

                            <template v-slot:row-details="row">
                                <b-card>
                                <ul>
                                    <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                </ul>
                                </b-card>
                            </template>
                            </b-table>

                            <!-- Info modal -->
                        <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
                        <pre>{{ infoModal.content }}</pre>
                        </b-modal>
                        </div>
                    </div>`,
        data: { //fields: ['id', 'name', 'age', 'place', 'email', 'created_at', { key: 'actions', label: 'Actions' }],
            dataValues,
            isBusy: false,
            infoModal: {
            id: 'info-modal',
            title: '',
            content: ''
            }
        },
        methods:{
            fetchItems,
            info(item, index, button) {
                this.infoModal.title = `Row index: ${index}`
                this.infoModal.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            resetInfoModal() {
                this.infoModal.title = ''
                this.infoModal.content = ''
            },
        },
        mounted: function(){
        console.log("Component is mounted");
        this.fetchItems();
        console.log(dataValues);
        }
    }

    const routes = [
        { path: '/foo', component: Foo },
        { path: '/bar', component: Bar },
        { path: '/dataitems', component: dataGridItems }
    ]

    const router = new VueRouter({
        routes
    })

    const app = new Vue({
        router
    }).$mount('#appp')
})();
