import{_ as $,j as S,C as D,u as I,l as M,r as _,o as i,c as d,w as K,s as f,q as C,F as z,e as F,H as L,d as x,t as w,h as c,R as O,g,k as B}from"./index.96126930.js";const T={mounted:(e,n)=>{const l=n.value.duration||500,h=n.value.animate,u=s=>{const v=e.scrollTop,a=e.scrollHeight-v,y=20;function H(t,p,b,N){return t/=N/2,t<1?b/2*t*t+p:(t-=1,-b/2*(t*(t-2)-1)+p)}function V(t){t+=y;const p=H(t,v,a,s);e.scrollTop=p,t<s&&setTimeout(()=>{V(t)},y)}V(0)},o=()=>{h?u(l):e.scrollTop=e.scrollHeight},r=new MutationObserver(o),m={childList:!0};r.observe(e,m)}};const U={class:"va-chat"},Y={class:"va-chat__message-text"},j={class:"va-chat__controls"},q=S({__name:"Chat",props:{modelValue:{default:()=>[{text:"Hello! So glad you liked my work. Do you want me to shoot you?",yours:!1},{text:"Yeah, that would be cool. Maybe this Sunday at 3 pm?",yours:!0},{text:"Sounds great! See you later!",yours:!1},{text:"Should I bring a lightbox with me?",yours:!0},{text:"No, thanks. There is no need. Can we set up a meeting earlier?",yours:!1},{text:"I'm working on Vuestic, so let's meet at 3pm. Thanks!",yours:!0}]},height:{default:"20rem"}},emits:["update:modelValue"],setup(e,{emit:n}){const l=e,{colors:h}=D(),{t:u}=I(),o=M("");function r(){!o.value||(n("update:modelValue",l.modelValue.concat({text:o.value,yours:!0})),o.value="")}return(m,s)=>{const v=_("va-input"),k=_("va-button");return i(),d("div",U,[K((i(),d("div",{class:"va-chat__body",style:C({height:e.height})},[(i(!0),d(z,null,F(e.modelValue,(a,y)=>(i(),d("div",{key:y,class:L(["va-chat__message",{"va-chat__message--yours":a.yours}]),style:C({backgroundColor:a.yours?f(h).primary:void 0})},[x("span",Y,w(a.text),1)],6))),128))],4)),[[f(T),{animate:!0,duration:500}]]),x("div",j,[c(v,{modelValue:o.value,"onUpdate:modelValue":s[0]||(s[0]=a=>o.value=a),placeholder:"Type your message...",class:"va-chat__input mr-2",onKeypress:O(r,["enter"])},null,8,["modelValue","onKeypress"]),c(k,{onClick:s[1]||(s[1]=a=>r())},{default:g(()=>[B(w(f(u)("chat.sendButton")),1)]),_:1})])])}}});var A=$(q,[["__scopeId","data-v-451f73c6"]]);const E={class:"chat"},P={class:"row"},R={class:"flex xs12 md12"},J=S({__name:"ChatPage",setup(e){const{t:n}=I(),l=M([{text:"Hello! So glad you liked my work. Do you want me to shoot you?",yours:!1},{text:"Yeah, that would be cool. Maybe this Sunday at 3 pm?",yours:!0},{text:"Sounds great! See you later!",yours:!1},{text:"Should I bring a lightbox with me?",yours:!0},{text:"No, thanks. There is no need. Can we set up a meeting earlier?",yours:!1},{text:"I'm working on Vuestic, so let's meet at 3pm. Thanks!",yours:!0}]);return(h,u)=>{const o=_("va-card-title"),r=_("va-card-content"),m=_("va-card");return i(),d("div",E,[x("div",P,[x("div",R,[c(m,null,{default:g(()=>[c(o,null,{default:g(()=>[B(w(f(n)("chat.title")),1)]),_:1}),c(r,null,{default:g(()=>[c(A,{modelValue:l.value,"onUpdate:modelValue":u[0]||(u[0]=s=>l.value=s)},null,8,["modelValue"])]),_:1})]),_:1})])])])}}});export{J as default};