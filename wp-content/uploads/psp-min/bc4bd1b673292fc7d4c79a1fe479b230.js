var ampCustomizePreview=(function(api){var component={};component.boot=function boot(data){api.bind('preview-ready',function(){api.preview.bind('active',function(){api.preview.send('amp-status',data)})})};return component})(wp.customize);