import{_ as F,a as u,r as l,o as w,f as y,g as o,h as n,d as r,t as f,k as a}from"./index.5bf0689d.js";import{d as C}from"./debounce.c03b9090.js";import{l as x}from"./index.54cc61ce.js";const I={name:"StockFavorite",components:{},data(){const e=[],t="";return{loading:!1,items:e,columns:[{key:"name",sortable:!0},{key:"ticker",sortable:!0},{key:"figi",sortable:!0},{key:"currency",width:80},{key:"rsi_hour",width:80}],input:t,filter:t,isDebounceInput:!1,isCustomFilteringFn:!1,filteredCount:e.length,selectedItemsEmitted:[],listPrice:Array,serverParams:{name:""}}},methods:{filterExact(e){var t;return this.filter===""?!0:((t=e==null?void 0:e.toString)==null?void 0:t.call(e))===this.filter},updateFilter(e){this.filter=e},debouncedUpdateFilter:C(function(e){this.updateFilter(e)},600),filterPrice:function(e){this.filter=e,this.input=e},fetchRows(){let e=this;this.loading=!0,u.request({method:"get",url:"/api/etf/favorites",params:this.serverParams,paramsSerializer:t=>x.stringify(t)}).then(t=>{e.items=t.data.etfs,e.loading=!1}).catch(t=>{console.log(t),e.loading=!1})},resetInfoModal(){this.infoModal.title="",this.infoModal.content=""},unfavorite:function(e,t){var c=this;this.loading=!0,u.get("/sanctum/csrf-cookie").then(_=>{u.post("/api/etf/unfavorite",{selRows:c.selectedItemsEmitted}).then(s=>{s.status?(console.log("\u0412\u044B\u0437\u0432\u0430\u043B\u0438 \u0430\u043B\u0435\u0440\u0442"),this.$vaToast.init({message:"Success",color:"success"}),this.fetchRows(),this.loading=!1):(console.log("\u041D\u0435 \u0440\u0430\u0431\u043E\u0442\u0430\u0435\u0442"),console.log(s.status),this.loading=!1)})})},getCellRsiLabel(e){return e<=30?"success":e>=70?"danger":"primary"}},computed:{customFilteringFn(){return this.isCustomFilteringFn?this.filterExact:void 0}},watch:{input(e){this.isDebounceInput?this.debouncedUpdateFilter(e):this.updateFilter(e)}},created(){this.fetchRows()}},S={class:"grid md:grid-cols-2 gap-6 mb-6"},R={class:"row"},E={class:"col"},V=a(" \u0423\u0431\u0440\u0430\u0442\u044C \u0438\u0437 \u0438\u0437\u0431\u0440\u0430\u043D\u043D\u043E\u0433\u043E "),N=["href"],P=a(" Number of filtered items: ");function B(e,t,c,_,s,d){const h=l("va-input"),p=l("va-button"),m=l("va-chip"),g=l("va-data-table"),v=l("va-alert"),b=l("va-card-content"),k=l("va-card");return w(),y(k,null,{default:o(()=>[n(b,null,{default:o(()=>[r("div",S,[n(h,{modelValue:s.input,"onUpdate:modelValue":t[0]||(t[0]=i=>s.input=i),placeholder:"Filter...",class:"w-full"},null,8,["modelValue"])]),r("div",R,[r("div",E,[n(p,{onClick:d.unfavorite},{default:o(()=>[V]),_:1},8,["onClick"])])]),n(g,{items:s.items,columns:s.columns,filter:s.filter,"filter-method":d.customFilteringFn,onFiltered:t[1]||(t[1]=i=>s.filteredCount=i.items.length),loading:s.loading,selectable:"","selected-color":"warning",onSelectionChange:t[2]||(t[2]=i=>s.selectedItemsEmitted=i.currentSelectedItems)},{"cell(name)":o(({rowData:i})=>[r("a",{class:"link",target:"_blank",href:"https://www.tinkoff.ru/invest/stocks/"+i.ticker},f(i.name),9,N)]),"cell(rsi_hour)":o(({rowData:i})=>[n(m,{size:"small",color:d.getCellRsiLabel(i.rsi_hour)},{default:o(()=>[a(f(i.rsi_hour),1)]),_:2},1032,["color"])]),_:1},8,["items","columns","filter","filter-method","loading"]),n(v,{class:"!mt-6",color:"info",outline:""},{default:o(()=>[P,n(m,null,{default:o(()=>[a(f(s.filteredCount),1)]),_:1})]),_:1})]),_:1})]),_:1})}var L=F(I,[["render",B],["__scopeId","data-v-0c3d76cc"]]);export{L as default};