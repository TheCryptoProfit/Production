<?php

defined('ABSPATH') or die("No script kiddies please!");

class SettingsView {
    
    function draw($model) {
    ?>
        <script type="text/javascript">
			(function ($) {
			    $( document ).ready(function() {
			        $('.dropdown-toggle').dropdown();
			        $('[data-toggle="tooltip"]').tooltip();  
			   
			        $( document.body ).on( 'click', '.dropdown-menu li', function( event ) {

                      var $target = $( event.currentTarget );
                      var value = $target.children().attr("data-format");
                       
                      $target.closest( '.dropdown' )
                         .find( '[data-bind="label"]' ).text( $target.text() )
                            .end()
                         .children( '.dropdown-toggle' ).attr("data-selected", value).dropdown( 'toggle' );
                         
                      
                
                      return false;
                
                    });
                    
                    $( document.body ).on( 'click', '.anchor-delete', function( event ) {

                      var $target = $( event.currentTarget );
                       
                      $target.closest( '.list-group-item' ).remove();
        
                      return false;
                
                   });
    
    			    var model = <?php echo json_encode($model) ?>;
    			    
    			    var loadModel = function()
                    {
                        
                    };
                    
                    var fillModel = function(model)
                    {
                        $("#apiKey").val(model.apiKey.value);
                        if (model.apiKey.error){
                            $("#apiKeyContainer").addClass("has-error");
                            $("#saveAPI").addClass("btn-danger").removeClass("btn-primary");
                        }
                        else
                        {
                            $("#saveAPI").removeClass("btn-danger").addClass("btn-primary");
                            $("#apiKeyContainer").removeClass("has-error");
                        }
                        
                        $("#backlinkCounter").val(model.backlinkSettings.counter.value);
                        $("#newPosts").prop('checked', model.backlinkSettings.newPosts == "true");
                        $("#randomPosts").prop('checked', model.backlinkSettings.randomPosts == "true");
                        $("#randomPostCounter").val(model.backlinkSettings.randomPostCounter.value);
                        
                        var randomPostDateFormatText = $("#randomPostDateFormat").parent().find( 'a[data-format="'+model.backlinkSettings.randomPostDateFormat.value+'"]' ).text();
                        $("#randomPostDateFormat").find( '[data-bind="label"]' ).text( randomPostDateFormatText );
                        $("#randomPostDateFormat").attr("data-selected", model.backlinkSettings.randomPostDateFormat.value);
                        
                        var sharedNetworksList_ul = $("#sharedNetworksList_ul");
                        sharedNetworksList_ul.text("");
                        for (var property in model.networkSettings.sharedNetworksList.list) {
                            if (model.networkSettings.sharedNetworksList.list.hasOwnProperty(property)) {
                                sharedNetworksList_ul.append(
                                    $("<li></li>")
                                    .addClass("dropdown-header")
                                    .text(property)
                                );
                                
                                var prop = model.networkSettings.sharedNetworksList.list[property];
                                for (var key = 0; key < prop.length; key ++)
                                {
                                    sharedNetworksList_ul.append(
                                        $("<li></li>")
                                        .addClass("padding-select")
                                        .append(
                                            $("<a></a>")
                                            .attr("href", "#")
                                            .attr("data-format", prop[key].id)
                                            .text(prop[key].name)
                                        )
                                    );
                                }
                            }
                        }
                        
                        var anchorsList_ul = $("#anchorsList");
                        anchorsList_ul.text("");
                        if (model.networkSettings.anchors)
                        {
                            for (var key = 0; key < model.networkSettings.anchors.length; key ++)
                            {
                                
                                anchorsList_ul.append(
                                    $("<li></li>")
                                    .addClass("list-group-item list-group-item-success")
                                    .append(
                                        $("<span></span>")
                                        .addClass("anchor-title")
                                        .attr("anchor-value", model.networkSettings.anchors[key])
                                        .text(model.networkSettings.anchors[key])
                                    )
                                    .append(
                                        $("<span></span>")
                                        .addClass("anchor-delete glyphicon glyphicon-remove")
                                    )
                                );
                            }
                        }
                        
                        var sharedNetworksListText = $("#sharedNetworksList").parent().find( 'a[data-format="'+model.networkSettings.sharedNetworksList.selected+'"]' ).text();
                        if (!sharedNetworksListText.length)
                            sharedNetworksListText = "Select network..";
                        $("#sharedNetworksList").find( '[data-bind="label"]' ).text( sharedNetworksListText );
                        $("#sharedNetworksList").attr("data-selected", model.networkSettings.sharedNetworksList.selected);
                        
                        $("#sharedNetworksEnabled").prop('checked', model.networkSettings.sharedNetworksEnabled == "true");
                        $("#submission1Enabled").prop('checked', model.myNetworks.submission1.enabled == "true");
                        $("#submission2Enabled").prop('checked', model.myNetworks.submission2.enabled == "true");
                        $("#submission3Enabled").prop('checked', model.myNetworks.submission3.enabled == "true");
                        
                        $("#submission1Value").val(model.myNetworks.submission1.value);
                        $("#submission2Value").val(model.myNetworks.submission2.value);
                        $("#submission3Value").val(model.myNetworks.submission3.value);
                        
                    };
                    
                    fillModel(model);
                    
                    var updateSettings = function(callback)
                    {
                        var data = {
                			'action': 'updateRankwyzSettings',
                			'model': model
                		};
    		
                        jQuery.post(ajaxurl, data)
                		.done(function(data) {
                		    var model = JSON.parse(data);
                		    fillModel(model);
                		})
                		.always(function() {
                		    callback();
                		})
                    };
                    
                    var saveModel = function()
                    {
                        var backLinkCounter =  $("#backlinkCounter").val();
                        var newPosts =  $("#newPosts").is(':checked');
                        var randomPosts =  $("#randomPosts").is(':checked');
                        var randomPostCounter = $("#randomPostCounter").val();
                        var randomPostFormat = $("#randomPostDateFormat").attr("data-selected");
                        
                        model["backlinkSettings"]["counter"]["value"] = backLinkCounter;
                        model["backlinkSettings"]["newPosts"] = newPosts;
                        model["backlinkSettings"]["randomPosts"] = randomPosts;
                        model["backlinkSettings"]["randomPostCounter"]["value"] = randomPostCounter;
                        model["backlinkSettings"]["randomPostDateFormat"]["value"] = randomPostFormat;
                        
                        var sharedNetworksSelected = $("#sharedNetworksList").attr("data-selected");
                        var sharedNetworksEnabled = $("#sharedNetworksEnabled").is(':checked');
                        var submission1Enabled = $("#submission1Enabled").is(':checked');
                        var submission2Enabled = $("#submission2Enabled").is(':checked');
                        var submission3Enabled = $("#submission3Enabled").is(':checked');
                        var submission1Value = $("#submission1Value").val();
                        var submission2Value = $("#submission2Value").val();
                        var submission3Value = $("#submission3Value").val();
                        
                        var anchors = [];
                        $('.anchor-title').each(function (index, value) { 
                            anchors.push($(value).attr("anchor-value"));
                        });
                        
                        model["networkSettings"]["sharedNetworksEnabled"] = sharedNetworksEnabled;
                        model["networkSettings"]["sharedNetworksList"]["selected"] = sharedNetworksSelected;
                        model["networkSettings"]["anchors"] = anchors;
                        
                        
                        model["myNetworks"]["submission1"]["enabled"] =  submission1Enabled;
                        model["myNetworks"]["submission2"]["enabled"] =  submission2Enabled;
                        model["myNetworks"]["submission3"]["enabled"] =  submission3Enabled;
                        
                        model["myNetworks"]["submission1"]["value"] =  submission1Value;
                        model["myNetworks"]["submission2"]["value"] =  submission2Value;
                        model["myNetworks"]["submission3"]["value"] =  submission3Value;
                        
                        var value =  $("#apiKey").val();
                        model["apiKey"]["value"] = value;
                         
                    }
                    
                    $("#saveAPI").on("click", function(){
                        var self = $(this);
                        
                        var $btn = self.button('loading')

                        saveModel();
                        
                        var updateCallback = function()
                        {
                            self.button('reset');
                        }
                        
                        updateSettings(updateCallback);
                    });
                    
                    
                    
                    $("#saveBackLinks").on("click", function(){
                        var self = $(this);
                        
                        var $btn = self.button('loading')

                        saveModel();
                        
                        var updateCallback = function()
                        {
                            self.button('reset');
                        }
                        
                        updateSettings(updateCallback);
                    });
                    
                    $("#saveNetworkSettings").on("click", function(){
                        var self = $(this);
                        
                        var $btn = self.button('loading')

                        saveModel();
                        
                        var updateCallback = function()
                        {
                            self.button('reset');
                        }
                        
                        updateSettings(updateCallback);
                    });
                    
                    var addAchorAction = function()
                    {
                        var newAnchor = $("#achorsAddField").val();
                        if (!newAnchor.length)
                            return;
                        
                        $("#achorsAddField").val("");
                        
                        var newList = $("<li></li>")
                                      .addClass("list-group-item list-group-item-success")
                                      .append(
                                          $("<span></span>")
                                          .addClass("anchor-title")
                                          .attr("anchor-value", newAnchor)
                                          .text(newAnchor)
                                      )
                                      .append(
                                          $("<span></span>")
                                          .addClass("anchor-delete glyphicon glyphicon-remove")
                                      );
                                      
                        $("#anchorsList").append(newList);
                    }
                    
                    $("#achorsAddField").keypress(function(e) {
                        if(e.which == 13) {
                            addAchorAction();
                        }
                    });
                    
                    $("#addAnchor").on("click", function(){
                        addAchorAction();
                    });
                    
                    
                
			    });
                
			})(jQuery);

    	</script>
    	
        <div class="settings-container bootstrap-wrapper">
          <form class="form-horizontal" role="form">
            <div class="settings-panel panel panel-primary">
              <div class="panel-heading">RankWyz Settings</div>
              <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label lb-md" for="apiKey_input">API Key</label>
                        <div class="col-sm-1"></div>
                        <div id="apiKeyContainer" class="input-group col-sm-8">
                            <span class="input-group-addon" id="basic-addon">
                                <a href="#" data-toggle="tooltip" title="Hooray!"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>
                            </span>
                            <input type="text" class="form-control api-input" name="apiKey" id="apiKey" placeholder="Your api key here">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" data-loading-text="Checking..." type="button" id="saveAPI">Save</button>
                            </span>
                        </div>
                    </div>
              </div>
            </div>
            
            <div class="settings-panel panel panel-primary">
                <!-- Default panel contents -->
                <div class="panel-heading">Backlink Settings</div>
                <div class="panel-body">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-8">
                        <div class="form-group center-block">
                            <label class="col-sm-5 align-left control-label lb-md" for="backlinkCounter">Backlink every post</label>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-4">
                                <input type="number" value="3" min="0" step="1" class="form-control" id="backlinkCounter" />
                            </div>
                            <label class="control-label lb-md" for="backlinkCounter">times</label> 
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="checkbox">  
                                  <label><input type="checkbox" id="newPosts" value="">New posts</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="checkbox">  
                                    <label><input type="checkbox" id="randomPosts" value="">Random existing post</label>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="form-group">
                            <div class="col-sm-6">
                               <label for="random_post_counter" class="control-label"> Backlink random post every</label>
                            </div>
                            <div class="col-sm-3">
                               <input type="number" value="3" min="0" step="1" class="form-control" id="randomPostCounter" />
                            </div>
                            <div class="col-sm-3">
                                <div class="dropdown">
                                  <button class="btn btn-default dropdown-toggle" type="button" id="randomPostDateFormat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span data-bind="label"></span>
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" aria-labelledby="randomPostDateFormat">
                                    <li><a href="#" data-format="hourly">Hour(s)</a></li>
                                    <li><a href="#" data-format="daily">Day(s)</a></li>
                                  </ul>
                                </div>
                            </div><!-- /input-group -->
                        </div><!-- /.row -->
                        <div class="form-group center-block">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-4">
                                <button class="btn btn-primary" data-loading-text="Saving..." type="button" id="saveBackLinks">Save</button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="settings-panel panel panel-primary">
              <!-- Default panel contents -->
              <div class="panel-heading">Network Settings</div>
              <div class="panel-body">
                 <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <div class="panel panel-default">
                          <div class="panel-heading">Shared Networks</div>
                          <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-5">
                                      <div class="checkbox">    
                                          <label class="control-label"><input type="checkbox" id="sharedNetworksEnabled" value="">Web2 Shared Network:</label>
                                      </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="dropdown">
                                          <button class="btn btn-default dropdown-toggle" type="button" id="sharedNetworksList" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                          <span data-bind="label">Select network</span>
                                          <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu" id="sharedNetworksList_ul" aria-labelledby="sharedNetworksList">
                                          </ul>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                                <div class="form-group">
                                    <div class="col-sm-5">
                                      <label class="checkbox-inline">Anchors:</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <div id="anchorsContainer" class="input-group">
                                            <input type="text" class="form-control" name="achorsAddField" id="achorsAddField" placeholder="Enter new anchors here">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="addAnchor">Add</button>
                                            </span>
                                        </div>
                                        <br/>
                                        <ul class="list-group anchors-list" id="anchorsList">
                                        </ul>
                                    </div>
                                </div><!-- /.row -->
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">My Networks</div>
                          <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label><input type="checkbox" id="submission1Enabled" value="">Submission 1:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control api-input" id="submission1Value" placeholder="Submission 1">
                                    </div>
                                </div><!-- /.row -->
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <div class="checkbox">    
                                            <label><input type="checkbox" id="submission2Enabled" value="">Submission 2:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control api-input" id="submission2Value" placeholder="Submission 2">
                                    </div>
                                </div><!-- /.row -->
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <div class="checkbox">      
                                            <label><input type="checkbox" id="submission3Enabled" value="">Submission 3:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control api-input" id="submission3Value" placeholder="Submission 3">
                                    </div>
                                </div><!-- /.row -->
                          </div>
                        </div>
                        <div class="form-group center-block">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-6">
                                <button class="btn btn-primary" data-loading-text="Saving..." type="button" id="saveNetworkSettings">Save</button>
                            </div>
                        </div>
                    </div>
              </div>
            </div>
            </form>
          </div>
    <?php
    }
}