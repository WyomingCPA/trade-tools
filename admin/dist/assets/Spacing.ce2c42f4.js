import{d as P,D as w,j as i,x as h,r as _,o as f,c as y,b as e,e as o,u as n,t as C,E as k,N as b,w as x,h as S}from"./index.28971dc4.js";import{_ as V}from"./ColorPresentation.85eb9846.js";import"./useToast.ffbb8e30.js";const N={class:"spacing-playground"},z={class:"row"},D={class:"flex xs12 sm6 md4"},U={class:"flex xs12 sm6 md2"},B={class:"flex xs12 sm6 md4"},M={class:"flex xs12 sm6 md2"},E={key:0,class:"row"},L={class:"flex xs12 content"},j={class:"code"},I={class:"row"},T={class:"flex xs12"},q={class:"playground-component"},A=e("div",{class:"playground-component__inner"},null,-1),F=[A],G={class:"row"},H={class:"flex xs12 sm6"},J={class:"flex xs12 sm6"},K=P({__name:"SpacingPlayground",setup($){const{t:a}=w(),p=i(["a","y","x","t","r","b","l"]),m=i(["1","2","3","4","5","auto"]),t=i("y"),d=i("x"),c=i("3"),r=i("3"),g=h(()=>t.value&&c.value?`m${t.value}-${c.value}`:""),v=h(()=>d.value&&r.value?`p${d.value}-${r.value}`:"");return(Q,l)=>{const u=_("va-select");return f(),y("div",N,[e("div",z,[e("div",D,[o(u,{modelValue:t.value,"onUpdate:modelValue":l[0]||(l[0]=s=>t.value=s),options:p.value,label:n(a)("spacingPlayground.margin"),"max-height":null,"no-clear":""},null,8,["modelValue","options","label"])]),e("div",U,[o(u,{modelValue:c.value,"onUpdate:modelValue":l[1]||(l[1]=s=>c.value=s),options:m.value,label:n(a)("spacingPlayground.value"),"max-height":null,"no-clear":""},null,8,["modelValue","options","label"])]),e("div",B,[o(u,{modelValue:d.value,"onUpdate:modelValue":l[2]||(l[2]=s=>d.value=s),options:p.value,label:n(a)("spacingPlayground.padding"),"max-height":null,"no-clear":""},null,8,["modelValue","options","label"])]),e("div",M,[o(u,{modelValue:r.value,"onUpdate:modelValue":l[3]||(l[3]=s=>r.value=s),options:m.value,label:n(a)("spacingPlayground.value"),"max-height":null,"no-clear":""},null,8,["modelValue","options","label"])])]),n(g)||n(v)?(f(),y("div",E,[e("div",L,[e("pre",j,'class="'+C((n(g)+" "+n(v)).trim())+'"',1)])])):k("",!0),e("div",I,[e("div",T,[e("div",q,[e("div",{class:b(["playground-component__margin",n(g)])},[e("div",{class:b(["playground-component__padding",n(v)])},F,2)],2)])])]),e("div",G,[e("div",H,[o(V,{color:"#ffd093",name:n(a)("spacingPlayground.margin"),width:40},null,8,["name"])]),e("div",J,[o(V,{color:"#c9f7db",name:n(a)("spacingPlayground.padding"),width:40},null,8,["name"])])])])}}}),O={class:"spacing flex xs12 md12"},Y=P({__name:"Spacing",setup($){const{t:a}=w();return(p,m)=>{const t=_("va-card-title"),d=_("va-card-content"),c=_("va-card");return f(),y("div",O,[o(c,null,{default:x(()=>[o(t,null,{default:x(()=>[S(C(n(a)("spacing.title")),1)]),_:1}),o(d,null,{default:x(()=>[o(K,{title:""})]),_:1})]),_:1})])}}});export{Y as default};