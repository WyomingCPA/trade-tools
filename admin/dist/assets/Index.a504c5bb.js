import{_ as y,a as d,r as o,o as F,f as w,g as l,h as i,d as r,k as u,t as x}from"./index.96126930.js";import{d as C}from"./debounce.7f3347cb.js";import{l as I}from"./index.7654b3c6.js";const k={name:"StockAll",components:{},data(){const e=[],t="";return{count:{type:Number},loading:!1,items:e,columns:[{key:"id",sortable:!0},{key:"symbol",sortable:!0}],input:t,filter:t,isDebounceInput:!1,isCustomFilteringFn:!1,filteredCount:e.length,filtered:e,selectedItemsEmitted:[],listPrice:Array,currentPage:1,serverParams:{symbol:""}}},methods:{filterExact(e){var t;return this.filter===""?!0:((t=e==null?void 0:e.toString)==null?void 0:t.call(e))===this.filter},updateParams(e){this.serverParams=Object.assign({},this.serverParams,e)},onPageChange(e){console.log(this.currentPage),this.updateParams({page:this.currentPage}),this.fetchRows()},onPerPageChange(e){this.updateParams({perPage:e.currentPerPage}),this.fetchRows()},onSearch(e){this.updateParams({name:e}),this.fetchRows()},updateFilter(e){this.filter=e},debouncedUpdateFilter:C(function(e){this.updateFilter(e)},600),filterPrice:function(e){this.filter=e,this.input=e},fetchRows(){let e=this;this.loading=!0,d.request({method:"post",url:"/api/cryptocurrency/all",params:this.serverParams,paramsSerializer:t=>I.stringify(t)}).then(t=>{e.items=t.data.models,e.count=t.data.count,e.loading=!1,console.log(this.pages)}).catch(t=>{console.log(t),e.loading=!1})},resetInfoModal(){this.infoModal.title="",this.infoModal.content=""},favorite:function(e,t){let a=this;this.loading=!0,console.log(a.selectedItemsEmitted),d.get("/sanctum/csrf-cookie").then(m=>{d.post("/api/cryptocurrency/favorite",{selRows:a.selectedItemsEmitted}).then(s=>{s.status?(console.log("\u0412\u044B\u0437\u0432\u0430\u043B\u0438 \u0430\u043B\u0435\u0440\u0442"),this.$vaToast.init({message:"\u0418\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442 \u0434\u043E\u0431\u0430\u0432\u043B\u0435\u043D \u0432 \u0438\u0437\u0431\u0440\u0430\u043D\u043D\u043E\u0435",color:"success"}),this.fetchRows(),a.loading=!1):(console.log("\u041D\u0435 \u0440\u0430\u0431\u043E\u0442\u0430\u0435\u0442"),console.log(s.status),a.loading=!1)})})}},computed:{customFilteringFn(){return this.isCustomFilteringFn?this.filterExact:void 0},pages(){return this.perPage&&this.perPage!==0?Math.ceil(this.count/this.perPage):this.count}},watch:{input(e){this.isDebounceInput?this.debouncedUpdateFilter(e):(console.log(e),this.updateParams({symbol:e}),this.fetchRows(),this.updateFilter(e))},currentPage:function(e){this.updateParams({page:e}),this.fetchRows()}},created(){this.fetchRows()}},R={class:"grid md:grid-cols-2 gap-6 mb-6"},S={class:"row"},E={class:"col"},V=u(" \u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u0432 \u0438\u0437\u0431\u0440\u0430\u043D\u043D\u043E\u0435 "),N={colspan:"6"},M={class:"flex justify-center mt-4"},U=u(" Number of filtered items: ");function A(e,t,a,m,s,c){const p=o("va-input"),f=o("va-button"),h=o("va-pagination"),g=o("va-data-table"),_=o("va-chip"),v=o("va-alert"),P=o("va-card-content"),b=o("va-card");return F(),w(b,null,{default:l(()=>[i(P,null,{default:l(()=>[r("div",R,[i(p,{modelValue:s.input,"onUpdate:modelValue":t[0]||(t[0]=n=>s.input=n),placeholder:"Filter...",class:"w-full"},null,8,["modelValue"])]),r("div",S,[r("div",E,[i(f,{onClick:c.favorite},{default:l(()=>[V]),_:1},8,["onClick"])])]),i(g,{items:s.items,columns:s.columns,filter:s.filter,"filter-method":c.customFilteringFn,onFiltered:t[2]||(t[2]=n=>s.filteredCount=n.items.length),loading:s.loading,selectable:"","selected-color":"warning",onSelectionChange:t[3]||(t[3]=n=>s.selectedItemsEmitted=n.currentSelectedItems)},{bodyAppend:l(()=>[r("tr",null,[r("td",N,[r("div",M,[i(h,{modelValue:s.currentPage,"onUpdate:modelValue":t[1]||(t[1]=n=>s.currentPage=n),pages:c.pages},null,8,["modelValue","pages"])])])])]),_:1},8,["items","columns","filter","filter-method","loading"]),i(v,{class:"!mt-6",color:"info",outline:""},{default:l(()=>[U,i(_,null,{default:l(()=>[u(x(s.count),1)]),_:1})]),_:1})]),_:1})]),_:1})}var T=y(k,[["render",A],["__scopeId","data-v-64f89be9"]]);export{T as default};