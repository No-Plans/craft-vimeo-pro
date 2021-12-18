/**
 * Craft Vimeo Pro plugin for Craft CMS
 *
 * CraftVimeoProField Field JS
 *
 * @author    No Plans
 * @copyright Copyright (c) 2020 No Plans
 * @link      https://no-plans.com/
 * @package   CraftVimeoPro
 * @since     1.0.0CraftVimeoProCraftVimeoProField
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "CraftVimeoProCraftVimeoProField",
        defaults = {
        },
        xhr,
        _this;

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            _this = this;
            //initiate search bar for input
            $(_this.element).searchApi();
        },

    };

    var utils = {

      compareDates : function(da, db) {
        var str = ""
        var dateDiff = Math.abs(da - db)
        if (dateDiff < 1000 * 60 * 10) {
          str = "10 min ago"
        } else if (dateDiff < 3600000) {
          str = "an hour ago"
        } else if (dateDiff < 3600000 * 6) {
          str = "" + Math.floor(dateDiff / 3600000 + 1) + " hours ago"
        } else if (dateDiff < 3600000 * 24) {
          str = "a day ago"
        } else if (dateDiff < 3600000 * 24 * 7) {
          str = "a week ago"
        }
        return str && ("less than " + str)  || ""
      },

      displayEntryThumb : function (entry) {
        return entry.pictures.sizes[2].link.replace(/\?.*$/, "");
      },

      displayEntryInfo : function (entry) {
        var now = new Date()
        var dateDiff = now - new Date(entry.created_time) 
        
        var date = entry.modified_time && utils.compareDates(now, new Date(entry.modified_time)) || ""
        date = date || "created " + utils.formatDate(new Date(entry.created_time))
        date = "<br />" + date
        
        return utils.toMMSS(entry.duration) + ' - ' + entry.width + 'x' + entry.height + date
      },

      displayEntryTitle : function (entry) {
        return entry.name
      },

      formatDate : function(date) {
        return date.toLocaleDateString({});
      },

      getVideoInfoString : function(data) {
        return [
          utils.toMMSS(data.duration),
          " - ",
          data.width,
          "x",
          data.height,
          "<br/>",
          "Last modified: ",
          utils.formatDate(new Date(data.created_time))
        ].join("");
      },

      parseVimeoId : function(id) {
        var match = (id || "").match(/[0-9]{6,12}/);
        if (!match || !match.length) { 
          return false 
        } else { 
          return match[0] 
        }
      },

      toMMSS : function(value) {
        var sec_num = parseInt(value, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num % 3600) / 60);
        var seconds = Math.floor((sec_num % 60));

        if (hours < 10) {
          hours = "0" + hours;
        }
        if (minutes < 10) {
          minutes = "0" + minutes;
        }
        if (seconds < 10) {
          seconds = "0" + seconds;
        }
        
        var output = minutes + ":" + seconds
        
        return hours > 0 ? hours + ':' + output : output;
      }

    };

    $.fn.searchApi = function(settings) {

        var keys = {
          enter: 13,
          escape: 27,
          upArrow: 38,
          downArrow: 40
        };

        var state = {
          active: false,
          currentIndex: -1,
          items: []
        };

        var $el = this;
        var $container = $el.find('.craft-vimeo-pro__container')
        var $prompt = $el.find('.craft-vimeo-pro__prompt');
        var $display = $el.find('.craft-vimeo-pro__display');
        var $errors = $el.find('.craft-vimeo-pro__errors');
        var $input = $el.find('.craft-vimeo-pro__input');
        var $results = $el.find('.craft-vimeo-pro__results');
        var $refresh = $el.find(".craft-vimeo-pro__refresh");
        var $remove = $el.find(".craft-vimeo-pro__remove");

        $prompt.on('focus', function() { query() });
        $prompt.on('blur', function() { $results.removeClass('active'); xhr.abort(); });
        $prompt.on('keyup', function(e) { query(e.target.value) });

        var initialData = getData();

        if (!!initialData) {
          initialData = JSON.parse(initialData);
          displayRefreshedData(initialData);
        }

        $refresh.on("click", function() {
          var id = utils.parseVimeoId( initialData.uri || JSON.parse($input.val()).uri );
          refresh(id);
        });

        $remove.on("click", function() {
          remove();
        });

        function query(str) {

            onLoading();

            var params = Object.assign(
            {
              type: "GET",
              success: function(data, textStatus, request) {
                onFinish();
                $results.addClass('active');
                populate(data);
              },
              error: function(request, textStatus, errorThrown) {
                if(errorThrown !== 'abort')
                  displayAlert(errorThrown);

                $container.removeClass('loading');
              }
            },
            createQuery(str)
            );
            
            if(xhr)
              xhr.abort();

            xhr = $.ajax(params);

        }

        function createQuery(query) {
            var fields =
              "name,link,pictures.sizes.link,link,uri,created_time,modified_time,duration,width,height";
            var url = query
              ? (
                  "https://api.vimeo.com/me/videos?query={query}&weak_search=true&per_page=30&fields=" +
                  fields
                ).replace(/\{query\}/, query)
              : "https://api.vimeo.com/me/videos?per_page=10&fields=" + fields;
            return {
              url: url,
              beforeSend: function(request) {
                request.setRequestHeader(
                  "Authorization",
                  "bearer " + _this.options.vimeoAuth
                );
              }
            };
          }

        function populate(resp) {
          // register and keep track of the item array for later
          var items = (state.items = []);
          // setup the drop down and attach it to the DOM
          var $ul = $('<div class="dropdown" />');
          $results.html($ul);
          // cycle through data to build drop down optinos
          resp.data.forEach(function(entry, index) {
            // setup option container and listeners
            var $li = $("<a/>");

            // associate element + data for laster use in listeners etc.
            items.push({ $el: $li, data: entry });

            $li.on("mousedown", function() {
              // NOTE: mousedown is used rather than click since,
              // blur hides the dropdown and cancels the click
              onSelect(entry);
            });

            $li.on("hover", function() {
              // sync keyboard navigation to hovered item
              //setIndex(index);
            });

            // thumbnail
            var imageSource = utils.displayEntryThumb(entry)
            $li_img = $('<img height="50" />').attr("src", imageSource);

            // description
            $li_title = $("<h5 />").text(utils.displayEntryTitle(entry));
            $li_info = $("<p />").html(utils.displayEntryInfo(entry));
            $li.append($li_img, 
                $("<div>").append($li_title, $li_info)
              );

            $ul.append($li);
          });
        }

        function onSelect(entry) {
          console.log('select', entry);
          $prompt.val("");
          refresh(entry.uri);
        }

        function remove() {
          setData();
          clearAlert();
          $prompt.val("");
          $input.val("");
          $container.removeClass("has-data");
        }

        function onLoading() {
          $container.addClass('loading');
        }

        function onFinish() {
          $container.removeClass('loading');
        }

        function refresh(id) {
          onLoading();
          clearAlert();
          id = utils.parseVimeoId(id);
          if (~~id == 0)
            return displayAlert(
              "Please provide a valid Vimeo ID. It should contain about 9 digits."
            );
          var API_URL = "service/vimeo-api";
          var fields =
            "name,link,pictures.sizes.link, pictures.sizes.width, pictures.sizes.height,link,uri,created_time,modified_time,duration,width,height,files";
          $(document).ready(function() {
            $.ajax({
              url: "https://api.vimeo.com/videos/" + id + "?fields=" + fields,
              type: "GET",
              dataType: "json",
              success: onDataLoaded,
              error: function(err) {
                displayAlert(err && err.statusText, 1);
              },
              beforeSend: function setAjaxAuthHeader(xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  "bearer " + _this.options.vimeoAuth
                );
              }
            });
          });
        }

        function onDataLoaded(data) {
          onFinish();
          if (!data) {
            return displayAlert(
              "The API didn't return any data.\nDouble check the ID or retry in a minute.",
              1
            );
          }
          
          if (typeof(data) !== 'object') {
            console.log('data', data)
            return displayAlert("The video was found but provides wrong data type '" + typeof(data) +"'")
          }
          
          console.log('success data', data)

          if (!data.files || data.files.length == 0) {
            return displayAlert(
              "The video '" +
                data.name +
                "' was found but doesn't provide any files.\nMake sure the video the video provide external access to its files.",
              1
            );
          }
          clearAlert();
          setData(data);

          displayRefreshedData(data);
        }

        function getData() {
          var val = $input.val();

          var unescaped = val.split(String.fromCharCode(92,92)).join(String.fromCharCode(92));
          return unescaped;
        }

        function setData(data) {
          var str = JSON.stringify(data);
          if(!str) {
            $input.val('');
            return;
          }

          var escaped = str.split(String.fromCharCode(92)).join(String.fromCharCode(92,92));
          $input.val(escaped);
        }

        function displayRefreshedData(data) {
          // var id = parse_vimeo_id(data.uri);
          // $entry.val(id);
          displayData(data);
        }

        function displayData(data) {
          $container.addClass("has-data");
          var $html;

          if ($display.is(":empty")) {
            $html = $("<div>")
              .addClass("craft-vimeo-pro__preview")
              // poster frame wrapped inside a link to the video
              .append(
                $("<a>")
                  // .attr({ href: insertLink, target: "_blank" })
                  .append($('<img data-name="image">'))
                  .append(
                    // video info showing beneath the poster frame
                    $("<div>")
                      .addClass("video-info")
                      // title
                      .append($("<h4>"))
                      // dimensions
                      .append($("<div>"))
                  )
              );
            $display.html($html);
          } else {
            $html = $display.first();
          }

          var insertCopy = utils.getVideoInfoString(data);
          var insertLink = data.link;
          var insertSrc = data.pictures.sizes[6].link;

          $html.find("a").attr({ href: insertLink, target: "_blank" });
          $html.find("a img").attr({ src: insertSrc });
          $html.find(".video-info h4").text(data.name);
          $html.find(".video-info div").html(insertCopy);

          $el.trigger("load-fix");
        }

        function displayAlert(msg, level) {
          
          msg = (msg || "").replace(/\n/g, "<br />");
          var $error = $('<li>' + msg + '</li>');
          $errors.append($error);
          
        }

        function clearAlert() {
          $errors.html("");
        }
    }

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
