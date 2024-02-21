<template>
    <va-card>
        <va-card-content>
            <va-form class="flex flex-col gap-6" ref="formRef">
                <VaInput v-model="minRange" class="mb-6" label="Min Range" placeholder="min" />
                <VaInput v-model="maxRange" class="mb-6" label="Max Range" placeholder="max" />
                <VaInput v-model="balance" class="mb-6" label="Balance" placeholder="Balance" />
                <va-button @click="update()"> Обновить </va-button>
            </va-form>
        </va-card-content>
    </va-card>
</template>
<script>
import axios from 'axios'


export default {
    name: 'group-create',
    components: {

    },
    data() {
        return {
            loading: false,
            minRange: 0,
            maxRange: 0,
            balance: 0,
        }
    },
    methods: {
        getData() {
            let self = this
            axios
                .get('/api/cryptocurrency/edit/' + this.$route.params.id)
                .then(function (response) {
                    self.minRange = response.data.model.min
                    self.maxRange = response.data.model.max
                    self.balance = response.data.model.balances
                    console.log(response.data.model.min)
                })
                .catch(function (error) {
                    console.error(error)
                })
        },
        async update() {
            let self = this
            this.loading = true
            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios
                    .post('/api/cryptocurrency/update', {
                        id: this.$route.params.id,
                        minRange: self.minRange,
                        maxRange: self.maxRange,
                        balance: self.balance,
                    })
                    .then((response) => {
                        if (response.status) {
                            this.$vaToast.init({ message: 'Pool обновлен', color: 'success' })
                            console.log('Pool обновлен');
                            self.loading = false
                        } else {
                            console.log('Не работает')
                            console.log(response.status)
                        }
                    })
                    .catch(function (error) {
                        console.log(response)
                        console.error(error)
                    })
            })
        },
    },
    mounted: function () {
        this.getData()
    },
}
</script>