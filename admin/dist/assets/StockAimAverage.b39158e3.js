import{_ as v,r as a,o as g,f as b,g as s,h as l,d as f,k as c}from"./index.96126930.js";const V={name:"stock-aim-average-calculator",data(){return{GeneralPriceStock:5.56,GeneralCountStock:549,ThisPriceStock:5.55,AimAveragePrice:5.5,Result:0}},methods:{handleCalculate(d){let e=parseFloat(this.GeneralPriceStock),n=1;for(;e>=this.AimAveragePrice;){console.log("\u0422\u0435\u043A\u0443\u0449\u0430\u044F \u0438\u0442\u0435\u0440\u0430\u0446\u0438\u044F"+n);let i=parseFloat((this.GeneralPriceStock*this.GeneralCountStock).toFixed(2)),t=parseFloat((n*this.ThisPriceStock+i).toFixed(2)),u=parseFloat((n+this.GeneralCountStock).toFixed(2));if(console.log(u),e=parseFloat((t/u).toFixed(7)),console.log(e),n>=400)break;n++}this.Result=n}}},S=c("Stock Aim Average"),A={class:"grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end"},C=c(" \u0420\u0430\u0441\u0441\u0447\u0438\u0442\u0430\u0442\u044C ");function P(d,e,n,i,t,u){const m=a("va-card-title"),r=a("va-input"),p=a("va-button"),_=a("va-card-content"),k=a("va-card");return g(),b(k,null,{default:s(()=>[l(m,null,{default:s(()=>[S]),_:1}),l(_,null,{default:s(()=>[f("div",A,[l(r,{modelValue:t.GeneralPriceStock,"onUpdate:modelValue":e[0]||(e[0]=o=>t.GeneralPriceStock=o),modelModifiers:{number:!0},type:"number",step:"any",label:"\u041E\u0431\u0449\u0430\u044F \u0446\u0435\u043D\u0430 \u0438\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442\u0430 \u0432 \u043F\u043E\u0440\u0442\u0444\u0435\u043B\u0435",counter:""},null,8,["modelValue"]),l(r,{modelValue:t.GeneralCountStock,"onUpdate:modelValue":e[1]||(e[1]=o=>t.GeneralCountStock=o),modelModifiers:{number:!0},type:"number",step:"any",counter:"",label:"\u041E\u0431\u0449\u0435\u0435 \u043A\u043E\u043B-\u0432\u043E \u0438\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442\u0430 \u0432 \u043F\u043E\u0440\u0442\u0444\u0435\u043B\u0435"},null,8,["modelValue"]),l(r,{modelValue:t.AimAveragePrice,"onUpdate:modelValue":e[2]||(e[2]=o=>t.AimAveragePrice=o),modelModifiers:{number:!0},type:"number",step:"any",counter:"",label:"Aim average(max) price"},null,8,["modelValue"]),l(r,{modelValue:t.ThisPriceStock,"onUpdate:modelValue":e[3]||(e[3]=o=>t.ThisPriceStock=o),modelModifiers:{number:!0},type:"number",step:"any",counter:"",label:"\u0422\u0435\u043A\u0443\u0449\u0430\u044F \u0446\u0435\u043D\u0430 \u0438\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442\u0430"},null,8,["modelValue"]),l(r,{modelValue:t.Result,"onUpdate:modelValue":e[4]||(e[4]=o=>t.Result=o),modelModifiers:{number:!0},type:"number",step:"any",counter:"",label:"Count Result"},null,8,["modelValue"]),l(p,{onClick:u.handleCalculate},{default:s(()=>[C]),_:1},8,["onClick"])])]),_:1})]),_:1})}var x=v(V,[["render",P]]);export{x as default};