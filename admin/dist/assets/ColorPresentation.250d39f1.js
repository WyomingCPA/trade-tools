import{g as k,j as l,A as b,y as d,r as w,o as u,c as p,e as x,w as B,b as s,n as N,q as m,t as v,s as S}from"./index.06ce08cf.js";import{u as T}from"./useToast-bc94f73d.72c0754a.js";const V={class:"color-presentation"},I={key:0,class:"color-presentation__description"},P={class:"color-presentation__name"},$={class:"color-presentation__text"},j=["value"],D=k({__name:"ColorPresentation",props:{color:{default:""},variant:{default:()=>[]},width:{default:0},name:{default:""},description:{default:""}},setup(o){const t=o,r=l({content:"Click to copy the color to clipboard",placement:"right"}),{getColor:a,getGradientBackground:_}=b(),i=d(()=>{const e=a(t.color);return t.variant.includes("gradient")?_(e):e}),f=d(()=>{const e=()=>{if(t.variant.includes("hovered"))return"brightness(115%)";if(t.variant.includes("pressed"))return"brightness(85%)"};return{background:i.value,filter:e(),width:t.width?`${t.width}px`:""}}),c=l();function h(){var e,n;(n=(e=navigator.clipboard)==null?void 0:e.writeText)==null||n.call(e,c.value.value).then(y)}const{init:g}=T();function y(){g({message:"The color's copied to your clipboard",position:"bottom-right",color:a(t.color)})}return(e,n)=>{const C=w("va-popover");return u(),p("div",V,[x(C,{color:"info",placement:r.value.placement,message:r.value.content},{default:B(()=>[s("div",{class:"color-presentation__color",style:N(m(f)),onClick:n[0]||(n[0]=q=>h())},null,4)]),_:1},8,["placement","message"]),o.name||o.description?(u(),p("div",I,[s("div",P,v(o.name),1),s("div",$,v(o.description),1)])):S("",!0),s("input",{ref_key:"hiddenInput",ref:c,value:m(i),class:"hidden-input"},null,8,j)])}}});export{D as _};