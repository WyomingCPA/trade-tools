import{m as v}from"./index.e8a87a39.js";function M(e){var r=typeof e;return e!=null&&(r=="object"||r=="function")}var L=M,B=typeof v=="object"&&v&&v.Object===Object&&v,F=B,U=F,D=typeof self=="object"&&self&&self.Object===Object&&self,H=U||D||Function("return this")(),N=H,X=N,q=function(){return X.Date.now()},z=q,J=/\s/;function K(e){for(var r=e.length;r--&&J.test(e.charAt(r)););return r}var Q=K,V=Q,Y=/^\s+/;function Z(e){return e&&e.slice(0,V(e)+1).replace(Y,"")}var ee=Z,re=N,te=re.Symbol,w=te,h=w,R=Object.prototype,ne=R.hasOwnProperty,ie=R.toString,l=h?h.toStringTag:void 0;function ae(e){var r=ne.call(e,l),i=e[l];try{e[l]=void 0;var a=!0}catch{}var f=ie.call(e);return a&&(r?e[l]=i:delete e[l]),f}var oe=ae,fe=Object.prototype,ce=fe.toString;function se(e){return ce.call(e)}var ue=se,x=w,be=oe,de=ue,me="[object Null]",le="[object Undefined]",I=x?x.toStringTag:void 0;function ge(e){return e==null?e===void 0?le:me:I&&I in Object(e)?be(e):de(e)}var ve=ge;function Te(e){return e!=null&&typeof e=="object"}var ye=Te,je=ve,Se=ye,$e="[object Symbol]";function Oe(e){return typeof e=="symbol"||Se(e)&&je(e)==$e}var pe=Oe,_e=ee,E=L,he=pe,k=0/0,xe=/^[-+]0x[0-9a-f]+$/i,Ie=/^0b[01]+$/i,Ee=/^0o[0-7]+$/i,ke=parseInt;function Ge(e){if(typeof e=="number")return e;if(he(e))return k;if(E(e)){var r=typeof e.valueOf=="function"?e.valueOf():e;e=E(r)?r+"":r}if(typeof e!="string")return e===0?e:+e;e=_e(e);var i=Ie.test(e);return i||Ee.test(e)?ke(e.slice(2),i?2:8):xe.test(e)?k:+e}var Le=Ge,Ne=L,S=z,G=Le,we="Expected a function",Re=Math.max,We=Math.min;function Pe(e,r,i){var a,f,u,s,n,c,b=0,$=!1,d=!1,T=!0;if(typeof e!="function")throw new TypeError(we);r=G(r)||0,Ne(i)&&($=!!i.leading,d="maxWait"in i,u=d?Re(G(i.maxWait)||0,r):u,T="trailing"in i?!!i.trailing:T);function y(t){var o=a,m=f;return a=f=void 0,b=t,s=e.apply(m,o),s}function W(t){return b=t,n=setTimeout(g,r),$?y(t):s}function P(t){var o=t-c,m=t-b,_=r-o;return d?We(_,u-m):_}function O(t){var o=t-c,m=t-b;return c===void 0||o>=r||o<0||d&&m>=u}function g(){var t=S();if(O(t))return p(t);n=setTimeout(g,P(t))}function p(t){return n=void 0,T&&a?y(t):(a=f=void 0,s)}function A(){n!==void 0&&clearTimeout(n),b=0,a=c=f=n=void 0}function C(){return n===void 0?s:p(S())}function j(){var t=S(),o=O(t);if(a=arguments,f=this,c=t,o){if(n===void 0)return W(c);if(d)return clearTimeout(n),n=setTimeout(g,r),y(c)}return n===void 0&&(n=setTimeout(g,r)),s}return j.cancel=A,j.flush=C,j}var Ae=Pe,Me=Ae;export{Me as d};