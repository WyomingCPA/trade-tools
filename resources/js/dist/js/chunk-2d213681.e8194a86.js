(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d213681"],{ad19:function(t,a,n){"use strict";n.r(a);var o=function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("trading-vue",{attrs:{data:this.$data}})},e=[],c=n("0042"),i=n.n(c),s=n("bc3a"),r=n.n(s),u={name:"app",components:{TradingVue:i.a},data:function(){return{ohlcv:[]}},methods:{fetchRows:function(){var t=this;this.loading=!0;var a=this.$route.params.id;r.a.request({method:"post",url:"/api/stock/indicator-tutci/"+a,params:this.serverParams,paramsSerializer:function(t){return qs.stringify(t)}}).then((function(a){t.ohlcv=a.data.candles,console.log(a.data),t.count=a.data.count,t.loading=!1})).catch((function(a){console.log(a),t.loading=!1}))}},created:function(){this.fetchRows()}},d=u,l=n("2877"),h=Object(l["a"])(d,o,e,!1,null,null,null);a["default"]=h.exports}}]);
//# sourceMappingURL=chunk-2d213681.e8194a86.js.map