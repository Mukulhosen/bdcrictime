!function(e){var t=/\.(.*)$/,i=function(){return!1},r=function(){return!0};e.Event.prototype.isPaused=i,e.Event.prototype.pause=function(){this,this.stopImmediatePropagation(),this.isPaused=r},e.Event.prototype.resume=function(){this.isPaused=this.isImmediatePropagationStopped=this.isPropagationStopped=i;var r=this.liveFired||this.currentTarget||this.target;e.event.special.default,this.type;if(this.handleObj.origHandler){var a=this.currentTarget;this.currentTarget=this.liveFired,this.liveFired=void 0,function(e,i){var r,a,s,n,l,o,p,d,h,u,c,g,v=[],m=[],f=jQuery._data(this,"events");if(e.liveFired===this||!f||!f.live||e.target.disabled||e.button&&"click"===e.type)return;e.namespace&&(c=new RegExp("(^|\\.)"+e.namespace.split(".").join("\\.(?:.*\\.)?")+"(\\.|$)"));e.liveFired=this;var y=f.live.slice(0);for(p=0;p<y.length;p++)(l=y[p]).origType.replace(t,"")===e.type?m.push(l.selector):y.splice(p--,1);for(n=jQuery(e.target).closest(m,e.currentTarget),d=0,h=n.length;d<h;d++)for(u=n[d],p=0;p<y.length;p++)l=y[p],u.selector!==l.selector||c&&!c.test(l.namespace)||u.elem.disabled||(o=u.elem,s=null,"mouseenter"!==l.preType&&"mouseleave"!==l.preType||(e.type=l.preType,(s=jQuery(e.relatedTarget).closest(l.selector)[0])&&jQuery.contains(o,s)&&(s=o)),s&&s===o||v.push({elem:o,handleObj:l,level:u.level}));for(d=0,h=v.length;d<h;d++)if(n=v[d],i)i===n.elem&&(i=void 0);else{if(a&&n.level>a)break;if(e.currentTarget=n.elem,e.data=n.handleObj.data,e.handleObj=n.handleObj,(!1===(g=n.handleObj.origHandler.apply(n.elem,arguments))||e.isPropagationStopped())&&(a=n.level,!1===g&&(r=!1),e.isImmediatePropagationStopped()))break}return r}.call(r,this,a),r=a}if(this.isImmediatePropagationStopped())return!1;this.firstPass=!0,this.isPropagationStopped()||e.event.trigger(this,[this.handleObj],r,!1)}}(jQuery);