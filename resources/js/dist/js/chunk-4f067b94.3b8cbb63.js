(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4f067b94"],{"1e91":function(e,t,n){"use strict";n.r(t);var o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("vue-good-table",{attrs:{theme:"nocturnal",columns:e.columns,rows:e.items,"sort-options":{enabled:!0},"line-numbers":!0,"pagination-options":{enabled:!0,mode:"records",perPage:100,position:"top",perPageDropdown:[200,300,500],dropdownAllowAll:!1,setCurrentPage:1,nextLabel:"next",prevLabel:"prev",rowsPerPageLabel:"Rows per page",ofLabel:"of",pageLabel:"page",allLabel:"All"},"search-options":{enabled:!0},"select-options":{enabled:!0}},on:{"on-selected-rows-change":e.selectionChanged},scopedSlots:e._u([{key:"table-row",fn:function(t){return["name"===t.column.field?n("span",[n("a",{attrs:{target:"_blank",href:"https://www.tinkoff.ru/invest/etfs/"+t.row.ticker}},[e._v(e._s(t.row.name))])]):"ticker"===t.column.field?n("span",[e._v(" "+e._s(t.row.ticker)+" ")]):"nominal"===t.column.field?n("span",[e._v(" "+e._s(t.row.nominal)+" ")]):"currency"===t.column.field?n("span",[e._v(" "+e._s(t.row.currency)+" ")]):"last_price"===t.column.field?n("span",[e._v(" "+e._s(t.row.last_price)+" ")]):e._e()]}}])},[n("div",{attrs:{slot:"selected-row-actions"},slot:"selected-row-actions"},[n("button",{on:{click:e.favorite}},[e._v("Favorite")])])])],1)},l=[],a=n("bc3a"),s=n.n(a),r=(n("dac4"),{name:"etf-all",data:function(){return{items:[{name:"-",ticker:"-",currency:"-",last_price:"-"}],columns:[{label:"Name",field:"name"},{label:"ticker",field:"ticker"},{label:"Валюта",field:"currency"},{label:"Последняя цена",field:"last_price",type:"number"}]}},methods:{getEtf:function(){var e=this;s.a.get("/api/etf/all").then((function(t){e.items=t.data.etfs})).catch((function(e){console.error(e)}))},selectionChanged:function(e){this.selRows=e.selectedRows},favorite:function(e,t){var n=this;this.loading=!0,s.a.get("/sanctum/csrf-cookie").then((function(e){s.a.post("/api/etf/favorite",{selRows:n.selRows}).then((function(e){e.status?(console.log("Вызвали алерт"),n.getEtf(),n.loading=!1):(console.log("Не работает"),console.log(e.status),n.loading=!1)}))}))}},mounted:function(){this.getEtf()}}),c=r,i=n("2877"),u=Object(i["a"])(c,o,l,!1,null,null,null);t["default"]=u.exports},dac4:function(e,t,n){}}]);
//# sourceMappingURL=chunk-4f067b94.3b8cbb63.js.map