// const Dropzone = require("dropzone");

(function($) {
    'use strict';

    function getvalue(mthis) {
        alert(mthis);
    }

    // convert byte to kb/mb
    function prosolwpclient_bytesToSize(a, b) {
        if (0 == a) return "0 Bytes";
        var c = 1024,
            d = b || 2,
            e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
            f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
    }

    // add char / substr into string
    function addStr(str, index, stringToAdd) {
        return str.substring(0, index) + stringToAdd + str.substring(index, str.length);
    }

    $(document).ready(function() {

        function isAlphaNumeric(str) {
            //regrex ([A-z0-9À-ž\s]){2,}
            var code, i, len;

            for (i = 0, len = str.length; i < len; i++) {
                code = str.charCodeAt(i);
                if (!(code == 32) && //space
                    !(code > 47 && code < 58) && // numeric (0-9)
                    !(code > 64 && code < 91) && // upper alpha (A-Z)
                    !(code > 96 && code < 123) && // lower alpha (a-z)
                    !(code > 127 && code < 383) // diacritics char	
                ) {
                    return false;
                }
            }
            return true;
        };

        //job search 
        $("#jobnamesearch").on("change", function() {
            if (!isAlphaNumeric($(this).val())) {
                alert('Please input alpha or numeric only');
                $(this).val('');
            }
        });
        $("#searchplacesearch").on("change", function() {
            if (!isAlphaNumeric($(this).val())) {
                alert('Please input alpha or numeric only');
                $(this).val('');
            }
        });
        $("#searchbtn").on("click", function() {
            $('table.jobsearch-resultcontainer').html('');
        });

        //pagination job search prosolObj.ajaxurl
        // @Url.Action("Action", "Controller")
        function refreshitemsearch(act) {
            $.ajax({
                type: "post",
                dataType: 'json',
                url: prosolObj.ajaxurl,
                data: {
                    action: 'proSol_paginationjobsearch',
                    security: prosolObj.nonce,
                    actbutton: act,
                    count_indexshowlist: $('.count_indexshowlist').val(),
                    pg_counter: $('.pg_counter').val(),
                    pg_start: $('.pg_start').val(),
                    pg_next: $('.pg_next').val(),
                    issite: $('.issite').val()
                },
                success: function(res) {
                    $('.jobsearch-resultcontainer > .jobsearch-perid').addClass("hidden");
                    $(".jobsearch-resultcontainer > .jobsearch-perid").each(function() {
                        var perid = $(this);
                        if ((perid.data("item") >= res.pg_start) && (perid.data("item") < res.pg_next)) {
                            perid.removeClass("hidden");
                        }
                    });

                    $('.pg_counter').val(res.pg_counter);
                    $('.pg_start').val(res.pg_start);
                    $('.pg_next').val(res.pg_next);
                    $('div.jobsearch-pagination').html(res.html);

                    // re-bind again after success call
                    $("#pg_prev").on("click", function() {
                        refreshitemsearch('pg_prev');
                    });

                    $("#pg_prevv").on("click", function() {
                        refreshitemsearch('pg_prevv');
                    });

                    $("#pg_next").on("click", function() {
                        refreshitemsearch('pg_next');
                    });

                    $("#pg_nextt").on("click", function() {
                        refreshitemsearch('pg_nextt');
                    });

                }
            });
        };

        $("#pg_prev").on("click", function() {
            refreshitemsearch('pg_prev');
        });

        $("#pg_prevv").on("click", function() {
            refreshitemsearch('pg_prevv');
        });

        $("#pg_next").on("click", function() {
            refreshitemsearch('pg_next');
        });

        $("#pg_nextt").on("click", function() {
            refreshitemsearch('pg_nextt');
        });


        'use strict';

        class Color {
            constructor(r, g, b) {
                this.set(r, g, b);
            }

            toString() {
                return `rgb(${Math.round(this.r)}, ${Math.round(this.g)}, ${Math.round(this.b)})`;
            }

            set(r, g, b) {
                this.r = this.clamp(r);
                this.g = this.clamp(g);
                this.b = this.clamp(b);
            }

            hueRotate(angle = 0) {
                angle = angle / 180 * Math.PI;
                const sin = Math.sin(angle);
                const cos = Math.cos(angle);

                this.multiply([
                    0.213 + cos * 0.787 - sin * 0.213,
                    0.715 - cos * 0.715 - sin * 0.715,
                    0.072 - cos * 0.072 + sin * 0.928,
                    0.213 - cos * 0.213 + sin * 0.143,
                    0.715 + cos * 0.285 + sin * 0.140,
                    0.072 - cos * 0.072 - sin * 0.283,
                    0.213 - cos * 0.213 - sin * 0.787,
                    0.715 - cos * 0.715 + sin * 0.715,
                    0.072 + cos * 0.928 + sin * 0.072,
                ]);
            }

            grayscale(value = 1) {
                this.multiply([
                    0.2126 + 0.7874 * (1 - value),
                    0.7152 - 0.7152 * (1 - value),
                    0.0722 - 0.0722 * (1 - value),
                    0.2126 - 0.2126 * (1 - value),
                    0.7152 + 0.2848 * (1 - value),
                    0.0722 - 0.0722 * (1 - value),
                    0.2126 - 0.2126 * (1 - value),
                    0.7152 - 0.7152 * (1 - value),
                    0.0722 + 0.9278 * (1 - value),
                ]);
            }

            sepia(value = 1) {
                this.multiply([
                    0.393 + 0.607 * (1 - value),
                    0.769 - 0.769 * (1 - value),
                    0.189 - 0.189 * (1 - value),
                    0.349 - 0.349 * (1 - value),
                    0.686 + 0.314 * (1 - value),
                    0.168 - 0.168 * (1 - value),
                    0.272 - 0.272 * (1 - value),
                    0.534 - 0.534 * (1 - value),
                    0.131 + 0.869 * (1 - value),
                ]);
            }

            saturate(value = 1) {
                this.multiply([
                    0.213 + 0.787 * value,
                    0.715 - 0.715 * value,
                    0.072 - 0.072 * value,
                    0.213 - 0.213 * value,
                    0.715 + 0.285 * value,
                    0.072 - 0.072 * value,
                    0.213 - 0.213 * value,
                    0.715 - 0.715 * value,
                    0.072 + 0.928 * value,
                ]);
            }

            multiply(matrix) {
                const newR = this.clamp(this.r * matrix[0] + this.g * matrix[1] + this.b * matrix[2]);
                const newG = this.clamp(this.r * matrix[3] + this.g * matrix[4] + this.b * matrix[5]);
                const newB = this.clamp(this.r * matrix[6] + this.g * matrix[7] + this.b * matrix[8]);
                this.r = newR;
                this.g = newG;
                this.b = newB;
            }

            brightness(value = 1) {
                this.linear(value);
            }
            contrast(value = 1) {
                this.linear(value, -(0.5 * value) + 0.5);
            }

            linear(slope = 1, intercept = 0) {
                this.r = this.clamp(this.r * slope + intercept * 255);
                this.g = this.clamp(this.g * slope + intercept * 255);
                this.b = this.clamp(this.b * slope + intercept * 255);
            }

            invert(value = 1) {
                this.r = this.clamp((value + this.r / 255 * (1 - 2 * value)) * 255);
                this.g = this.clamp((value + this.g / 255 * (1 - 2 * value)) * 255);
                this.b = this.clamp((value + this.b / 255 * (1 - 2 * value)) * 255);
            }

            hsl() {
                // Code taken from https://stackoverflow.com/a/9493060/2688027, licensed under CC BY-SA.
                const r = this.r / 255;
                const g = this.g / 255;
                const b = this.b / 255;
                const max = Math.max(r, g, b);
                const min = Math.min(r, g, b);
                let h, s, l = (max + min) / 2;

                if (max === min) {
                    h = s = 0;
                } else {
                    const d = max - min;
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                    switch (max) {
                        case r:
                            h = (g - b) / d + (g < b ? 6 : 0);
                            break;

                        case g:
                            h = (b - r) / d + 2;
                            break;

                        case b:
                            h = (r - g) / d + 4;
                            break;
                    }
                    h /= 6;
                }

                return {
                    h: h * 100,
                    s: s * 100,
                    l: l * 100,
                };
            }

            clamp(value) {
                if (value > 255) {
                    value = 255;
                } else if (value < 0) {
                    value = 0;
                }
                return value;
            }
        }

        class Solver {
            constructor(target, baseColor) {
                this.target = target;
                this.targetHSL = target.hsl();
                this.reusedColor = new Color(0, 0, 0);
            }

            solve() {
                const result = this.solveNarrow(this.solveWide());
                return {
                    values: result.values,
                    loss: result.loss,
                    filter: this.css(result.values),
                };
            }

            solveWide() {
                const A = 5;
                const c = 15;
                const a = [60, 180, 18000, 600, 1.2, 1.2];

                let best = { loss: Infinity };
                for (let i = 0; best.loss > 25 && i < 3; i++) {
                    const initial = [50, 20, 3750, 50, 100, 100];
                    const result = this.spsa(A, a, c, initial, 1000);
                    if (result.loss < best.loss) {
                        best = result;
                    }
                }
                return best;
            }

            solveNarrow(wide) {
                const A = wide.loss;
                const c = 2;
                const A1 = A + 1;
                const a = [0.25 * A1, 0.25 * A1, A1, 0.25 * A1, 0.2 * A1, 0.2 * A1];
                return this.spsa(A, a, c, wide.values, 500);
            }

            spsa(A, a, c, values, iters) {
                const alpha = 1;
                const gamma = 0.16666666666666666;

                let best = null;
                let bestLoss = Infinity;
                const deltas = new Array(6);
                const highArgs = new Array(6);
                const lowArgs = new Array(6);

                for (let k = 0; k < iters; k++) {
                    const ck = c / Math.pow(k + 1, gamma);
                    for (let i = 0; i < 6; i++) {
                        deltas[i] = Math.random() > 0.5 ? 1 : -1;
                        highArgs[i] = values[i] + ck * deltas[i];
                        lowArgs[i] = values[i] - ck * deltas[i];
                    }

                    const lossDiff = this.loss(highArgs) - this.loss(lowArgs);
                    for (let i = 0; i < 6; i++) {
                        const g = lossDiff / (2 * ck) * deltas[i];
                        const ak = a[i] / Math.pow(A + k + 1, alpha);
                        values[i] = fix(values[i] - ak * g, i);
                    }

                    const loss = this.loss(values);
                    if (loss < bestLoss) {
                        best = values.slice(0);
                        bestLoss = loss;
                    }
                }
                return { values: best, loss: bestLoss };

                function fix(value, idx) {
                    let max = 100;
                    if (idx === 2 /* saturate */ ) {
                        max = 7500;
                    } else if (idx === 4 /* brightness */ || idx === 5 /* contrast */ ) {
                        max = 200;
                    }

                    if (idx === 3 /* hue-rotate */ ) {
                        if (value > max) {
                            value %= max;
                        } else if (value < 0) {
                            value = max + value % max;
                        }
                    } else if (value < 0) {
                        value = 0;
                    } else if (value > max) {
                        value = max;
                    }
                    return value;
                }
            }

            loss(filters) {
                // Argument is array of percentages.
                const color = this.reusedColor;
                color.set(0, 0, 0);

                color.invert(filters[0] / 100);
                color.sepia(filters[1] / 100);
                color.saturate(filters[2] / 100);
                color.hueRotate(filters[3] * 3.6);
                color.brightness(filters[4] / 100);
                color.contrast(filters[5] / 100);

                const colorHSL = color.hsl();
                return (
                    Math.abs(color.r - this.target.r) +
                    Math.abs(color.g - this.target.g) +
                    Math.abs(color.b - this.target.b) +
                    Math.abs(colorHSL.h - this.targetHSL.h) +
                    Math.abs(colorHSL.s - this.targetHSL.s) +
                    Math.abs(colorHSL.l - this.targetHSL.l)
                );
            }

            css(filters) {
                function fmt(idx, multiplier = 1) {
                    return Math.round(filters[idx] * multiplier);
                }
                return `filter: invert(${fmt(0)}%) sepia(${fmt(1)}%) saturate(${fmt(2)}%) hue-rotate(${fmt(3, 3.6)}deg) brightness(${fmt(4)}%) contrast(${fmt(5)}%);`;
            }
        }

        function hexToRgb(hex) {
            // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
            const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
            hex = hex.replace(shorthandRegex, (m, r, g, b) => {
                return r + r + g + g + b + b;
            });

            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? [
                    parseInt(result[1], 16),
                    parseInt(result[2], 16),
                    parseInt(result[3], 16),
                ] :
                null;
        }

        $(document).ready(() => {
            $('button.execute').click(() => {
                const rgb = hexToRgb($('input.target').val());
                if (rgb.length !== 3) {
                    alert('Invalid format!');
                    return;
                }

                const color = new Color(rgb[0], rgb[1], rgb[2]);
                const solver = new Solver(color);
                const result = solver.solve();

                let lossMsg;
                if (result.loss < 1) {
                    lossMsg = 'This is a perfect result.';
                } else if (result.loss < 5) {
                    lossMsg = 'The is close enough.';
                } else if (result.loss < 15) {
                    lossMsg = 'The color is somewhat off. Consider running it again.';
                } else {
                    lossMsg = 'The color is extremely off. Run it again!';
                }

                $('.realPixel').css('background-color', color.toString());
                $('.filterPixel').attr('style', result.filter);
                $('.filterDetail').text(result.filter);
                $('.lossDetail').html(`Loss: ${result.loss.toFixed(1)}. <b>${lossMsg}</b>`);
            });


        });


        setInterval(function() {
            $('#conclud_active_ofbutton').on('click', function() {
                var $this = $(this);
                $this.parents('#activityModal').siblings('#prosoljobApplyForm').children('#step1').find('.edu-tab-content div').each(function() {
                    var each_ofthis = $(this);
                    if (each_ofthis.hasClass('active')) {
                        var id = each_ofthis.attr('id');
                        var increment_id = id.substr(id.length - 1);
                        if (increment_id == 0) {
                            if (each_ofthis.length > 0) {
                                $(".edu-0[data-required]").attr("required", "true");
                                //var divs = document.querySelectorAll(".edu-0[data-required]"), i;
                                //for (i = 0; i < divs.length; ++i) {	
                                // if(divs[i].id=='pswp-edu-foact-0' && eduActivity > 0){
                                // 	// don't set required
                                // } else if(divs[i].id=='pswp-edu-business-0' && eduBusiness > 0){
                                // 	// don't set required
                                // } else {
                                // 	divs[i].attr("required","true");
                                // }	
                                //}	

                                // $("#pswp-group-0").attr("required","true");
                                // $("#pswp-training-practice-0").attr("required","true");
                                // $("#pswp-beginning-date-0").attr("required","true");
                                // $("#pswp-end-date-0").attr("required","true");

                            }

                            var edugroup = $("#pswp-group-0").val();
                            var edutraining = $("#pswp-training-practice-0").val();
                            var eduBegDt = $("#pswp-beginning-date-0").val();
                            var eduEndDt = $("#pswp-end-date-0").val();
                            var eduPostcode = $("#pswp-edu-postcode-0").val();
                            var eduTown = $("#pswp-edu-town-0").val();
                            var eduCountry = $("#pswp-edu-country-0").val();
                            var eduFederalState = $("#pswp-edu-federal-state-0").val();
                            var edulevelEducation = $("#pswp-education-0").val();
                            var eduDescription = $("#pswp-description-0").val();
                            var eduActivity = $('#activity_selection_wrapper li').length;
                            var eduBusiness = $('#business_selection_wrapper li').length;

                            var edugroupChk = $("#pswp-group-0");
                            var edutrainingChk = $("#pswp-training-practice-0");
                            var eduBegDtChk = $("#pswp-beginning-date-0");
                            var eduEndDtChk = $("#pswp-end-date-0");
                            var edutab = document.getElementById("edu_tab").querySelector('li');
                            if (edugroup == 0) {
                                edutraining = '';
                            }

                            if (eduBusiness > 0) {
                                $("#pswp-edu-business-0").removeAttr("required");
                                if ($("#pswp-edu-business-0").hasClass('error')) {
                                    $("#pswp-edu-business-0").siblings('p.error').remove();
                                }
                            }

                            if (eduActivity > 0) {
                                $("#pswp-edu-foact-0").removeAttr("required");
                                if ($("#pswp-edu-foact-0").hasClass('error')) {
                                    $("#pswp-edu-foact-0").siblings('p.error').remove();
                                }
                            }

                            // $(".edu-0[data-required]").each(function(index, element){
                            // 	var $el=$(element);
                            // 	if($el.id !="pswp-edu-foact-0"){
                            // 		$el.attr("required","true");
                            // 	}									

                            // 	if($el.val() != ''){
                            // 		$el.removeAttr("required");
                            // 	}					
                            // 	if($el.hasClass('error')){
                            // 		$el.removeClass('error');
                            // 		$el.siblings('p.error').remove();	
                            // 	}
                            // });


                            if (edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === '' && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' && eduActivity === 0 && eduBusiness === 0) {
                                $("#pswp-group-0").removeAttr("required");
                                $("#pswp-training-practice-0").removeAttr("required");
                                $("#pswp-beginning-date-0").removeAttr("required");
                                $("#pswp-end-date-0").removeAttr("required");

                                if (edugroupChk.hasClass('error')) {
                                    $("#pswp-group-0").removeClass("error");
                                    $("#pswp-group-0").siblings('p.error').remove();
                                }

                                if (edutrainingChk.hasClass('error')) {
                                    $("#pswp-training-practice-0").removeClass("error");
                                    $("#pswp-training-practice-0").siblings('p.error').remove();

                                }

                                if (eduBegDtChk.hasClass('error')) {
                                    $("#pswp-beginning-date-0").removeClass("error");
                                    $("#pswp-beginning-date-0").siblings('p.error').remove();
                                }

                                if (eduEndDtChk.hasClass('error')) {
                                    $("#pswp-end-date-0").removeClass("error");
                                    $("#pswp-end-date-0").siblings('p.error').remove();

                                }
                                if (edutab.classList.contains('active')) {
                                    document.getElementById("edu_tab").querySelector('li').classList.remove("error-tab");
                                }

                            }
                        } else {
                            if (each_ofthis.length > 0) {
                                $(".edu-" + increment_id + "[data-required]").attr("required", "true");
                                // $("#pswp-group-"+increment_id).attr("required","true");
                                // $("#pswp-training-practice-"+increment_id).attr("required","true");
                                // $("#pswp-beginning-date-"+increment_id).attr("required","true");
                                // $("#pswp-end-date-"+increment_id).attr("required","true");
                            }

                            var edugroup = $("#pswp-group-" + increment_id).val();
                            var edutraining = $("#pswp-training-practice-" + increment_id).val();
                            var eduBegDt = $("#pswp-beginning-date-" + increment_id).val();
                            var eduEndDt = $("#pswp-end-date-" + increment_id).val();
                            var eduPostcode = $("#pswp-edu-postcode-" + increment_id).val();
                            var eduTown = $("#pswp-edu-town-" + increment_id).val();
                            var eduCountry = $("#pswp-edu-country-" + increment_id).val();
                            var eduFederalState = $("#pswp-edu-federal-state-" + increment_id).val();
                            var edulevelEducation = $("#pswp-education-" + increment_id).val();
                            var eduDescription = $("#pswp-description-" + increment_id).val();
                            var parentactivity = $('.acti_selec_wrap-' + increment_id + ' li').length;
                            if (parentactivity !== null) {
                                eduActivity = $('.acti_selec_wrap-' + increment_id + ' li').length;
                            }
                            var parentbusiness = $('.busi_sele_wrap-' + increment_id + ' li').length;

                            if (parentbusiness !== null) {
                                eduBusiness = $('.busi_sele_wrap-' + increment_id + ' li').length;

                            }
                            var edugroupChk = $("#pswp-group-" + increment_id);
                            var edutrainingChk = $("#pswp-training-practice-" + increment_id);
                            var eduBegDtChk = $("#pswp-beginning-date-" + increment_id);
                            var eduEndDtChk = $("#pswp-end-date-" + increment_id);
                            var edutab = document.getElementById("edu_tab").getElementsByTagName('li');
                            if (edugroup == 0) {
                                edutraining = '';
                            }
                            if (eduActivity > 0) {
                                $("#pswp-edu-foact-" + increment_id).removeAttr("required");
                                if ($("#pswp-edu-foact-" + increment_id).hasClass('error')) {
                                    $("#pswp-edu-foact-" + increment_id).siblings('p.error').remove();
                                }
                            }
                            if (eduBusiness > 0) {
                                $("#pswp-edu-business-" + increment_id).removeAttr("required");
                                if ($("#pswp-edu-business-" + increment_id).hasClass('error')) {
                                    $("#pswp-edu-business-" + increment_id).siblings('p.error').remove();
                                }
                            }

                            if (edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === '' && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' && eduActivity === undefined && eduBusiness === undefined) {
                                $("#pswp-group-" + increment_id).removeAttr("required");
                                $("#pswp-training-practice-" + increment_id).removeAttr("required");
                                $("#pswp-beginning-date-" + increment_id).removeAttr("required");
                                $("#pswp-end-date-" + increment_id).removeAttr("required");

                                if (edugroupChk.hasClass('error')) {
                                    $("#pswp-group-" + increment_id).removeClass("error");
                                    $("#pswp-group-" + increment_id).siblings('p.error').remove();
                                }

                                if (edutrainingChk.hasClass('error')) {
                                    $("#pswp-training-practice-" + increment_id).removeClass("error");
                                    $("#pswp-training-practice-" + increment_id).siblings('p.error').remove();
                                }

                                if (eduBegDtChk.hasClass('error')) {
                                    $("#pswp-beginning-date-" + increment_id).removeClass("error");
                                    $("#pswp-beginning-date-" + increment_id).siblings('p.error').remove();
                                }

                                if (eduEndDtChk.hasClass('error')) {
                                    $("#pswp-end-date-" + increment_id).removeClass("error");
                                    $("#pswp-end-date-" + increment_id).siblings('p.error').remove();
                                }
                                for (var i = 0; i < edutab.length; ++i) {
                                    if (edutab[i].classList.contains('active')) {
                                        edutab[i].classList.remove("error-tab");
                                    }
                                }
                            }
                        }
                    }
                });
            });

            $('#conclud_business_ofbutton').on('click', function() {
                var $this = $(this);
                $this.parents('#businessModal').siblings('#prosoljobApplyForm').children('#step1').find('.edu-tab-content div').each(function() {
                    var each_ofthis = $(this);
                    if (each_ofthis.hasClass('active')) {
                        var id = each_ofthis.attr('id');
                        var increment_id = id.substr(id.length - 1);
                        if (increment_id == 0) {
                            if (each_ofthis.length > 0) {
                                $(".edu-0[data-required]").attr("required", "true");
                                // $("#pswp-group-0").attr("required","true");
                                // $("#pswp-training-practice-0").attr("required","true");
                                // $("#pswp-beginning-date-0").attr("required","true");
                                // $("#pswp-end-date-0").attr("required","true");
                            }

                            var edugroup = $("#pswp-group-0").val();
                            var edutraining = $("#pswp-training-practice-0").val();
                            var eduBegDt = $("#pswp-beginning-date-0").val();
                            var eduEndDt = $("#pswp-end-date-0").val();
                            var eduPostcode = $("#pswp-edu-postcode-0").val();
                            var eduTown = $("#pswp-edu-town-0").val();
                            var eduCountry = $("#pswp-edu-country-0").val();
                            var eduFederalState = $("#pswp-edu-federal-state-0").val();
                            var edulevelEducation = $("#pswp-education-0").val();
                            var eduDescription = $("#pswp-description-0").val();
                            var eduActivity = $('#activity_selection_wrapper li').length;
                            var eduBusiness = $('#business_selection_wrapper li').length;

                            var edugroupChk = $("#pswp-group-0");
                            var edutrainingChk = $("#pswp-training-practice-0");
                            var eduBegDtChk = $("#pswp-beginning-date-0");
                            var eduEndDtChk = $("#pswp-end-date-0");
                            var edutab = document.getElementById("edu_tab").querySelector('li');
                            if (edugroup == 0) {
                                edutraining = '';
                            }
                            if (eduActivity > 0) {
                                $("#pswp-edu-foact-0").removeAttr("required");
                                if ($("#pswp-edu-foact-0").hasClass('error')) {
                                    $("#pswp-edu-foact-0").siblings('p.error').remove();
                                }
                            }
                            if (eduBusiness > 0) {
                                $("#pswp-edu-business-0").removeAttr("required");
                                if ($("#pswp-edu-business-0").hasClass('error')) {
                                    $("#pswp-edu-business-0").siblings('p.error').remove();
                                }
                            }

                            if (edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === '' && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' && eduActivity === 0 && eduBusiness === 0) {

                                $("#pswp-group-0").removeAttr("required");
                                $("#pswp-training-practice-0").removeAttr("required");
                                $("#pswp-beginning-date-0").removeAttr("required");
                                $("#pswp-end-date-0").removeAttr("required");

                                if (edugroupChk.hasClass('error')) {
                                    $("#pswp-group-0").removeClass("error");
                                    $("#pswp-group-0").siblings('p.error').remove();
                                }

                                if (edutrainingChk.hasClass('error')) {
                                    $("#pswp-training-practice-0").removeClass("error");
                                    $("#pswp-training-practice-0").siblings('p.error').remove();

                                }

                                if (eduBegDtChk.hasClass('error')) {
                                    $("#pswp-beginning-date-0").removeClass("error");
                                    $("#pswp-beginning-date-0").siblings('p.error').remove();
                                }


                                if (eduEndDtChk.hasClass('error')) {
                                    $("#pswp-end-date-0").removeClass("error");
                                    $("#pswp-end-date-0").siblings('p.error').remove();

                                }
                                if (edutab.classList.contains('active')) {
                                    document.getElementById("edu_tab").querySelector('li').classList.remove("error-tab");
                                }

                            }
                        } else {
                            if (each_ofthis.length > 0) {
                                $(".edu-" + increment_id + "[data-required]").attr("required", "true");
                                // $("#pswp-group-"+increment_id).attr("required","true");
                                // $("#pswp-training-practice-"+increment_id).attr("required","true");
                                // $("#pswp-beginning-date-"+increment_id).attr("required","true");
                                // $("#pswp-end-date-"+increment_id).attr("required","true");
                            }

                            var edugroup = $("#pswp-group-" + increment_id).val();
                            var edutraining = $("#pswp-training-practice-" + increment_id).val();
                            var eduBegDt = $("#pswp-beginning-date-" + increment_id).val();
                            var eduEndDt = $("#pswp-end-date-" + increment_id).val();
                            var eduPostcode = $("#pswp-edu-postcode-" + increment_id).val();
                            var eduTown = $("#pswp-edu-town-" + increment_id).val();
                            var eduCountry = $("#pswp-edu-country-" + increment_id).val();
                            var eduFederalState = $("#pswp-edu-federal-state-" + increment_id).val();
                            var edulevelEducation = $("#pswp-education-" + increment_id).val();
                            var eduDescription = $("#pswp-description-" + increment_id).val();
                            var parentactivity = $('.acti_selec_wrap-' + increment_id + 'li').length;
                            if (parentactivity !== null) {
                                eduActivity = $('.acti_selec_wrap-' + increment_id + ' li').length;
                            }
                            var parentbusiness = $('.busi_sele_wrap-' + increment_id + ' li').length;

                            if (parentbusiness !== null) {
                                eduBusiness = $('.busi_sele_wrap-' + increment_id + ' li').length;

                            }
                            var edugroupChk = $("#pswp-group-" + increment_id);
                            var edutrainingChk = $("#pswp-training-practice-" + increment_id);
                            var eduBegDtChk = $("#pswp-beginning-date-" + increment_id);
                            var eduEndDtChk = $("#pswp-end-date-" + increment_id);
                            var edutab = document.getElementById("edu_tab").getElementsByTagName('li');
                            if (edugroup == 0) {
                                edutraining = '';
                            }
                            if (eduActivity > 0) {
                                $("#pswp-edu-foact-" + increment_id).removeAttr("required");
                                if ($("#pswp-edu-foact-" + increment_id).hasClass('error')) {
                                    $("#pswp-edu-foact-" + increment_id).siblings('p.error').remove();
                                }
                            }
                            if (eduBusiness > 0) {
                                $("#pswp-edu-business-" + increment_id).removeAttr("required");
                                if ($("#pswp-edu-business-" + increment_id).hasClass('error')) {
                                    $("#pswp-edu-business-" + increment_id).siblings('p.error').remove();
                                }
                            }

                            if (edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === '' && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' && eduActivity === undefined && eduBusiness === undefined) {
                                $("#pswp-group-" + increment_id).removeAttr("required");
                                $("#pswp-training-practice-" + increment_id).removeAttr("required");
                                $("#pswp-beginning-date-" + increment_id).removeAttr("required");
                                $("#pswp-end-date-" + increment_id).removeAttr("required");

                                if (edugroupChk.hasClass('error')) {
                                    $("#pswp-group-" + increment_id).removeClass("error");
                                    $("#pswp-group-" + increment_id).siblings('p.error').remove();
                                }

                                if (edutrainingChk.hasClass('error')) {
                                    $("#pswp-training-practice-" + increment_id).removeClass("error");
                                    $("#pswp-training-practice-" + increment_id).siblings('p.error').remove();
                                }

                                if (eduBegDtChk.hasClass('error')) {
                                    $("#pswp-beginning-date-" + increment_id).removeClass("error");
                                    $("#pswp-beginning-date-" + increment_id).siblings('p.error').remove();
                                }

                                if (eduEndDtChk.hasClass('error')) {
                                    $("#pswp-end-date-" + increment_id).removeClass("error");
                                    $("#pswp-end-date-" + increment_id).siblings('p.error').remove();
                                }
                                for (var i = 0; i < edutab.length; ++i) {
                                    if (edutab[i].classList.contains('active')) {
                                        edutab[i].classList.remove("error-tab");
                                    }
                                }
                            }
                        }
                    }
                });
            });
        }, 2000);

        $.validator.setDefaults({ ignore: ":hidden:not(select)" }); //for all select

        $.extend($.validator.messages, {
            required: prosolObj.required,
            remote: prosolObj.remote,
            email: prosolObj.email,
            url: prosolObj.url,
            date: prosolObj.date,
            dateISO: prosolObj.dateISO,
            number: prosolObj.number,
            digits: prosolObj.digits,
            creditcard: prosolObj.creditcard,
            equalTo: prosolObj.equalTo,
            extension: prosolObj.extension,
            maxlength: $.validator.format(prosolObj.maxlength),
            minlength: $.validator.format(prosolObj.minlength),
            rangelength: $.validator.format(prosolObj.rangelength),
            range: $.validator.format(prosolObj.range),
            max: $.validator.format(prosolObj.max),
            min: $.validator.format(prosolObj.min),
        });

        $(".prosolwpclient-chosen-select").chosen({ width: '100%' });

        var start = new Date();
        start.setFullYear(start.getFullYear() - 100);
        var end = new Date();
        end.setFullYear(end.getFullYear() - 16);

        // application form jquery ui date picker restrict future
        $(".pswpuidatepicker-restrictfucture").datepicker({
            dateFormat: 'dd.mm.yy',
            // maxDate    : new Date,
            changeMonth: true,
            changeYear: true,
            minDate: start,
            maxDate: end,
            yearRange: start.getFullYear() + ':' + end.getFullYear(),
            onClose: function() {
                $(this).valid();
            }
        });

        // date of birth can manual input
        $(".pswpuidatepicker-restrictfucture").on('blur', function() {
            var inp = $(this).val();
            inp = inp.replace(/\./g, '');
            inp = inp.replace(/\//g, '');
            inp = inp.replace(/\\/g, '');
            inp = inp.replace(/\-/g, '');

            var newinp = addStr(inp, inp.length - 4, '.');
            var chkdate = newinp.split(".");

            if (chkdate[0].length < 4) {
                if (chkdate[0].length == 2) {
                    newinp = addStr(newinp, 0, '0');
                    newinp = addStr(newinp, 2, '0');
                } else {
                    newinp = addStr(newinp, 2, '0');
                }
            }
            newinp = addStr(newinp, 2, '.');

            var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
            if (dateRegex.test(newinp) && newinp.length == 10) {
                $(this).val(newinp);
                $(this).valid();
                $(this).removeClass("input-validation-error");
            } else {
                $(this).addClass("input-validation-error");
                $(this).next("p.error").show();
            }
        });

        // availibilty from can manual input
        $(".pswpuidatepicker").on('blur', function() {
            var inp = $(this).val();
            inp = inp.replace(/\./g, '');
            inp = inp.replace(/\//g, '');
            inp = inp.replace(/\\/g, '');
            inp = inp.replace(/\-/g, '');

            var newinp = addStr(inp, inp.length - 4, '.');
            var chkdate = newinp.split(".");

            if (chkdate[0].length < 4) {
                if (chkdate[0].length == 2) {
                    newinp = addStr(newinp, 0, '0');
                    newinp = addStr(newinp, 2, '0');
                } else {
                    newinp = addStr(newinp, 2, '0');
                }
            }
            newinp = addStr(newinp, 2, '.');

            var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
            if (dateRegex.test(newinp) && newinp.length == 10) {
                $(this).val(newinp);
                $(this).valid();
                $(this).removeClass("input-validation-error");
            } else {
                $(this).addClass("input-validation-error");
                $(this).next("p.error").show();
            }
        });


        // application form jquery ui date picker
        $(".pswpuidatepicker").datepicker({
            dateFormat: 'dd.mm.yy',
            changeMonth: true,
            changeYear: true,
            minDate: new Date(),
            onClose: function() {
                $(this).valid();
            }
        });

        // not using application form jquery ui year picker
        $(document).on('focus', ".pswpuiyearpicker", function() {
            $(this).datepicker({
                dateFormat: 'yy',
                changeYear: true,

            });
        });


        var $job_search_form = $('.job-search-form');

        // filter job search form checkbox profession group
        $job_search_form.on('keyup', '#filter_group', function() {
            var $this = $(this);
            var value = $this.val().toLowerCase();

            $(".profession-groups li").filter(function() {
                var $this = $(this);
                $this.toggle($this.text().toLowerCase().indexOf(value) > -1);
            });
        });

        var $full_app_form = $('#prosolfull_app_form');
        var $application_personal_info = $full_app_form.find('.application-info-personal');
        var $application_edu_info = $full_app_form.find('.application-info-education');
        var $application_exp_info = $full_app_form.find('.application-info-experience');
        var $application_expertise_info = $full_app_form.find('.application-info-expertise');
        var $application_side_info = $full_app_form.find('.application-info-side-dishes');
        var $onepage_policy = $full_app_form.find('.onepage-policy');

        var $jobModal = $full_app_form.find('#jobModal');
        var $activityModal = $full_app_form.find('#activityModal');
        var $businessModal = $full_app_form.find('#businessModal');
        var $knowledge_data = $full_app_form.find('#prosoljobApplyForm #step3 .application-info-expertise  ');

        var countpolicy = 1;

        var countcheck = 0;
        if ($('.isrec').val() == 1) {
            countpolicy++;
            if ($('#pswp-agree6').data("mand") != "*") {
                countpolicy--;
            }
        }
        if ($('#pswp-agree1').data("mand") != "*") {
            countpolicy--;
        }

        // no need, redundant, already done in public/js/jquery.formtowizard.js
        if (countpolicy != 0) {
            // prevent user to click send button if agree checkbox doesn't checked and when only have step1 activated
            $("#applicationSubmitBtn").prop("disabled", true);
        } else {
            $("#applicationSubmitBtn").removeAttr("disabled");
        }

        $application_personal_info.find('#pswp-agree1').on('click', function() {
            if ($(this).is(':checked') && countpolicy != "0") {
                if ($(this).data("mand") == "*") {
                    countcheck++;
                }
            } else {
                if (countcheck > 0) {
                    countcheck--;
                }
            }
            if (countcheck == countpolicy) {
                $application_personal_info.find("#step0Next").prop("disabled", false);
                $("#applicationSubmitBtn").prop("disabled", false);
            } else {
                $application_personal_info.find("#step0Next").prop("disabled", true);
                $("#applicationSubmitBtn").prop("disabled", true);
            }
        });
        $application_personal_info.find('#pswp-agree6').on('click', function() {
            if ($(this).is(':checked') && countpolicy != "0") {
                if ($(this).data("mand") == "*") {
                    countcheck++;
                }
            } else {
                if (countcheck > 0) {
                    countcheck--;
                }
            }
            if (countcheck == countpolicy) {
                $application_personal_info.find("#step0Next").prop("disabled", false);
                $("#applicationSubmitBtn").prop("disabled", false);
            } else {
                $application_personal_info.find("#step0Next").prop("disabled", true);
                $("#applicationSubmitBtn").prop("disabled", true);
            }
        });

        // policy for one page created in public\templates\prosolwpclientjobapply
        $onepage_policy.find('#pswp-agree1').on('click', function() {
            if ($(this).is(':checked') && countpolicy != "0") {
                if ($(this).data("mand") == "*") {
                    countcheck++;
                }
            } else {
                if (countcheck > 0) {
                    countcheck--;
                }
            }
            if (countcheck == countpolicy) {
                $("#applicationSubmitBtn").prop("disabled", false);
            } else {
                $("#applicationSubmitBtn").prop("disabled", true);
            }
        });
        $onepage_policy.find('#pswp-agree6').on('click', function() {
            if ($(this).is(':checked') && countpolicy != "0") {
                if ($(this).data("mand") == "*") {
                    countcheck++;
                }
            } else {
                if (countcheck > 0) {
                    countcheck--;
                }
            }
            if (countcheck == countpolicy) {
                $("#applicationSubmitBtn").prop("disabled", false);
            } else {
                $("#applicationSubmitBtn").prop("disabled", true);
            }
        });

        // filter application form job modal checkbox profession group
        $jobModal.on('keyup', '#pswp-filter-occupation', function() {
            var $this = $(this);
            var value = $this.val().toLowerCase();

            $(".job-profession-groups li").filter(function() {
                var $this = $(this);
                $this.toggle($this.text().toLowerCase().indexOf(value) > -1)
            });
        });

        // filter application form Field of Activity modal checkbox operation areas
        $activityModal.on('keyup', '#pswp-filter-area', function() {
            var $this = $(this);
            var $parent = $this.parents('.modal-body');
            var value = $this.val().toLowerCase();

            $parent.find(".operation-areas li").filter(function() {
                var $this = $(this);
                $this.toggle($this.text().toLowerCase().indexOf(value) > -1)
            });
        });

        // filter application form Business modal checkbox NACE
        $businessModal.on('keyup', '#pswp-filter-nace', function() {
            var $this = $(this);
            var value = $this.val().toLowerCase();

            $(".nace-groups li").filter(function() {
                var $this = $(this);
                $this.toggle($this.text().toLowerCase().indexOf(value) > -1)
            });
        });

        var $job_selection_template = $application_personal_info.find('#job_selection_template').html();
        Mustache.parse($job_selection_template); // optional, speeds up future uses

        var $job_selected_ids = [];

        // job modal to check occupation type
        $jobModal.find('.job-modal-section').on('change', 'input[type=checkbox]', function() {
            var $this = $(this);

            var is_checked = $this.is(':checked');
            var $checkbox_val = $this.val();
            var $checkbox_text = $.trim($this.parent('label').text());
            var $checked_count = $('.job-modal-section').find('input[type=checkbox]:checked').length;

            if (is_checked) {
                $job_selected_ids.push($checkbox_val);

                var $rendered = Mustache.render($job_selection_template, {
                    jobid: $checkbox_val,
                    job_title: $checkbox_text,
                });

                $application_personal_info.find('#job_selection_wrapper').append($rendered);

                $application_personal_info.find('.job-btn-modal').text(prosolObj.to_edit);

                $application_personal_info.find('.profession_tempo').remove();

                var $job_btn_modal = $application_personal_info.find('.job-btn-modal');
                if ($job_btn_modal.next('p').hasClass('error')) {
                    $job_btn_modal.next('p').css('display', 'none');
                }
            } else {
                var $index = $job_selected_ids.indexOf($checkbox_val);
                if ($index > -1) {
                    $job_selected_ids.splice($index, 1);
                }

                $application_personal_info.find('.job-li-' + $checkbox_val).remove();

                if (!($checked_count > 0)) {
                    $application_personal_info.find('.job-btn-modal').text(prosolObj.choose);
                    $application_personal_info.find('#job_selection_wrapper').html('<input type="hidden" class="profession_tempo" name="profession[]" required data-rule-required="true">');
                    var $job_btn_modal = $application_personal_info.find('.job-btn-modal');
                    if ($job_btn_modal.next('p').hasClass('error')) {
                        $job_btn_modal.next('p').css('display', 'block');
                    }
                }
            }
        });

        // remove selected job from list
        $application_personal_info.on('click', '.job-remove', function() {
            var $this = $(this);
            var $jobid = $this.data('jobid');

            $this.parents('.job-selection-wrap').remove();

            var $index = $job_selected_ids.indexOf($jobid.toString());
            if ($index > -1) {
                $job_selected_ids.splice($index, 1);
            }

            if ($job_selected_ids.length === 0) {
                $application_personal_info.find('.job-btn-modal').text(prosolObj.choose);
                $application_personal_info.find('#job_selection_wrapper').html('<input type="hidden" class="profession_tempo" name="profession[]" required data-rule-required="true">');

                var $job_btn_modal = $application_personal_info.find('.job-btn-modal');
                if ($job_btn_modal.next('p').hasClass('error')) {
                    $job_btn_modal.next('p').css('display', 'block');
                }
            }
        });

        // job modal on show check global selected job
        $jobModal.on('show.bs.modal', function() {
            $jobModal.find('input:checkbox').removeAttr('checked');
            $.each($job_selected_ids, function(index, value) {
                $jobModal.find('input[name="professioncheckbox"][value="' + value.toString() + '"]').prop("checked", true);
            });
        });

        // add default value for job modal
        if (typeof($('.prof_id_mustache').val()) !== 'undefined') {
            var profid = $('.prof_id_mustache').val();
            var selprof = $('.prof_showinappli_mustache').val();
            var profid_arr = profid.split(",");
            var selprof_arr = selprof.split(",");
            for (var i = 0; i < profid_arr.length; ++i) {
                //checked only when selected = 1
                if (selprof_arr[i] == '1') {
                    $jobModal.find('input[name="professioncheckbox"][value="' + profid_arr[i] + '"]').trigger("click");
                }
            }
        }
        //job finish

        //activity start
        var $operation_areas_template = $full_app_form.find('#operation_areas_template').html();
        Mustache.parse($operation_areas_template); // optional, speeds up future uses

        var $activity_modaltrack = 0;

        var $activity_selected_ids = [
            []
        ];

        $activityModal.on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            $activity_modaltrack = button.data('activity-modaltrack');

            var $rendered = Mustache.render($operation_areas_template);

            $full_app_form.find('#operation_areas_wrapper').append($rendered);

            // activity modal on show check global selected activity
            // $activityModal.find('input:checkbox').removeAttr('checked');
            $.each($activity_selected_ids[$activity_modaltrack], function(index, value) {
                $activityModal.find('input[name="operationareacheckbox"][value="' + value.toString() + '"]').prop("checked", true);
            });
        });

        // activity modal to check operational area
        $full_app_form.on('change', '.activity-modal-section input[type=checkbox]', function() {
            var $this = $(this);
            var $parent = $this.parents('.activity-modal-section');
            // var $track = $parent.data('track');
            var $track = $activity_modaltrack;

            var $grandparent = $application_edu_info.find('.activity-section-wrap-' + $track);

            var is_checked = $this.is(':checked');
            var $checkbox_val = $this.val();
            var $checkbox_text = $.trim($this.parent('label').text());
            var $checked_count = $parent.find('input[type=checkbox]:checked').length;

            if (is_checked) {
                var $activity_selection_template = $application_edu_info.find('#activity_selection_template').html();
                Mustache.parse($activity_selection_template); // optional, speeds up future uses

                $activity_selected_ids[$track].push($checkbox_val);

                var $rendered = Mustache.render($activity_selection_template, {
                    index: $track,
                    track: $track,
                    activityid: $checkbox_val,
                    activity_title: $checkbox_text,
                });

                $grandparent.find('#activity_selection_wrapper').append($rendered);

                $grandparent.find('.activity-btn-modal').text(prosolObj.to_edit);

            } else {
                var $index = $activity_selected_ids[$track].indexOf($checkbox_val);
                if ($index > -1) {
                    $activity_selected_ids[$track].splice($index, 1);
                }

                $grandparent.find('.activity-li-' + $checkbox_val).remove();

                if (!($checked_count > 0)) {
                    $grandparent.find('.activity-btn-modal').text(prosolObj.choose);
                    $grandparent.find('.activity_selection_wrapper').html('');
                }
            }
        });

        // remove selected activity from list
        $application_edu_info.on('click', '.activity-remove', function() {
            var $this = $(this);
            var $activityid = $this.data('activityid');
            var $track = $this.data('track');

            $this.parents('.activity-selection-wrap').remove();

            var $index = $activity_selected_ids[$track].indexOf($activityid.toString());
            if ($index > -1) {
                $activity_selected_ids[$track].splice($index, 1);
            }

            if ($activity_selected_ids[$track].length === 0) {
                var $grandparent = $application_edu_info.find('.activity-section-wrap-' + $track);
                $grandparent.find('.activity-btn-modal').text(prosolObj.choose);
                //remove required on foact
                var cntfill = 0;
                $(".edu-" + $track + "[data-required]").each(function() {
                    if ($(this).val() == "")
                        cntfill++;
                });

                if (cntfill == $(".edu-" + $track + "[data-required]").length) {
                    $(".edu-" + $track + "[data-required]").removeAttr("required");
                    if ($(".edu-" + $track + "[data-required]").hasClass('error')) {
                        $(".edu-" + $track + "[data-required]").removeClass('error');
                        $(".edu-" + $track + "[data-required]").siblings('p.error').remove();
                    }
                } else {
                    var eduRequired = $('#pswp-edu-foact-0').attr('data-required');
                    $('#pswp-edu-foact-' + $track).attr('required', eduRequired);
                }
            }
        });

        // on hide activity modal operation area checkbox html empty, it will generate by mustache on modal show
        $activityModal.on('hidden.bs.modal', function(event) {
            $full_app_form.find('#pswp-filter-area').val('');
            $full_app_form.find('#operation_areas_wrapper').html('');
        });
        //activity finish

        //business start
        var $nace_groups_template = $full_app_form.find('#nace_groups_template').html();
        Mustache.parse($nace_groups_template); // optional, speeds up future uses

        var $business_modaltrack = 0;

        var $business_selected_ids = [
            []
        ];

        $businessModal.on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            $business_modaltrack = button.data('business-modaltrack');

            var $rendered = Mustache.render($nace_groups_template);

            $full_app_form.find('#nace_groups_wrapper').append($rendered);

            // business modal on show check global selected business
            // $businessModal.find('input:checkbox').removeAttr('checked');
            $.each($business_selected_ids[$business_modaltrack], function(index, value) {
                $businessModal.find('input[name="nacecheckbox"][value="' + value.toString() + '"]').prop("checked", true);
            });
        });

        // business modal to check business type
        $full_app_form.on('change', '.business-modal-section input[type=checkbox]', function() {
            var $this = $(this);
            var $parent = $this.parents('.business-modal-section');
            // var $track = $parent.data('track');
            var $track = $business_modaltrack;

            var $grandparent = $application_edu_info.find('.business-section-wrap-' + $track);

            var is_checked = $this.is(':checked');
            var $checkbox_val = $this.val();
            var $checkbox_text = $.trim($this.parent('label').text());
            var $checked_count = $parent.find('input[type=checkbox]:checked').length;

            if (is_checked) {
                var $business_selection_template = $application_edu_info.find('#business_selection_template').html();
                Mustache.parse($business_selection_template); // optional, speeds up future uses

                $business_selected_ids[$track].push($checkbox_val);

                var $rendered = Mustache.render($business_selection_template, {
                    index: $track,
                    track: $track,
                    businessid: $checkbox_val,
                    business_title: $checkbox_text,
                });

                $grandparent.find('#business_selection_wrapper').append($rendered);

                $grandparent.find('.business-btn-modal').text(prosolObj.to_edit);
            } else {
                var $index = $business_selected_ids[$track].indexOf($checkbox_val);
                if ($index > -1) {
                    $business_selected_ids[$track].splice($index, 1);
                }

                $grandparent.find('.business-li-' + $checkbox_val).remove();

                if (!($checked_count > 0)) {
                    $grandparent.find('.business-btn-modal').text(prosolObj.choose);
                    $grandparent.find('.business_selection_wrapper').html('');
                }
            }
        });

        // remove selected business from list
        $application_edu_info.on('click', '.business-remove', function() {
            var $this = $(this);
            var $businessid = $this.data('businessid');
            var $track = $this.data('track');

            $this.parents('.business-selection-wrap').remove();

            var $index = $business_selected_ids[$track].indexOf($businessid.toString());
            if ($index > -1) {
                $business_selected_ids[$track].splice($index, 1);
            }

            if ($business_selected_ids[$track].length === 0) {
                var $grandparent = $application_edu_info.find('.business-section-wrap-' + $track);
                $grandparent.find('.business-btn-modal').text(prosolObj.choose);
                //remove required on business
                var cntfill = 0;
                $(".edu-" + $track + "[data-required]").each(function() {
                    if ($(this).val() == "")
                        cntfill++;
                });

                if (cntfill == $(".edu-" + $track + "[data-required]").length) {
                    $(".edu-" + $track + "[data-required]").removeAttr("required");
                    if ($(".edu-" + $track + "[data-required]").hasClass('error')) {
                        $(".edu-" + $track + "[data-required]").removeClass('error');
                        $(".edu-" + $track + "[data-required]").siblings('p.error').remove();
                    }
                } else {
                    var eduRequired = $('#pswp-edu-business-0').attr('data-required');
                    $('#pswp-edu-business-' + $track).attr('required', eduRequired);
                }
            }
        });

        // on hide business modal operation area checkbox html empty, it will generate by mustache on modal show
        $businessModal.on('hidden.bs.modal', function(event) {
            $full_app_form.find('#pswp-filter-nace').val('');
            $full_app_form.find('#nace_groups_wrapper').html('');
        });
        //business finish

        // group change will change training in education apply section
        $application_edu_info.on('change', '.group-selection', function() {
            var $this = $(this);
            var $track = $this.data('track');
            var $group_id = $this.val();

            $.ajax({
                type: "post",
                dataType: 'json',
                url: prosolObj.ajaxurl,
                data: {
                    action: "proSol_groupSelectionToTrainingCallback",
                    security: prosolObj.nonce,
                    group_id: $group_id,
                },
                success: function(data) {
                    var $return_html = '<option value="">' + prosolObj.training_practice_ph + '</option>';
                    if (data.length > 0) {
                        $.each(data, function(key, item) {
                            $return_html += '<option value="' + item.lookupId + '">' + item.name + '</option>';
                        });
                    }

                    $('.training-practice-section-' + $track).find('.training-practice').empty().append($return_html).trigger("chosen:updated")
                }

            });
        });
        //$knowledge_data.find('.alert-info').on('change', '.knowledge_data', function() {

        // country change will change federal state in personal education, experience apply section
        $('fieldset').on('change', '.pswp-country-selection', function() {
            var $this = $(this);
            var $track = $this.data('track');
            var $country_code = $this.val();

            var $fieldset = $this.parents('fieldset');
            $.ajax({
                type: "post",
                dataType: 'json',
                url: prosolObj.ajaxurl,
                data: {
                    action: "proSol_countrySelectionToFederalCallback",
                    security: prosolObj.nonce,
                    country_code: $country_code,
                },
                success: function(data) {
                    if (data.length === 0) {
                        alert(prosolObj.federal_list_empty);
                    }
                    var $return_html = '<option value="">' + prosolObj.federal_ph + '</option>';
                    if (data.length > 0) {
                        $.each(data, function(key, item) {
                            $return_html += '<option value="' + item.federalId + '">' + item.name + '</option>';
                        });
                    }

                    $fieldset.find('.pswp-federal-selection-' + $track)
                        .empty().append($return_html).trigger("chosen:updated")
                }
            });
        });

        var $pswp_limit_edu_exp_tabs = 10;
        var $pswp_added_edu_tabs = 1;
        var $pswp_added_exp_tabs = 1;
        // bootstrap edu/experience incremental add more
        $full_app_form.find(".incremental-nav-tabs").on("click", "a", function(e) {
            e.preventDefault();
            var $this = $(this);
            if (!$this.hasClass('pswp-add-edu')) {
                $this.tab('show');
            }
            if (!$this.hasClass('pswp-add-exp')) {
                $this.tab('show');
            }
        }).on("click", "span", function(e) {
            e.preventDefault();
            var $this = $(this);
            var $parent_ul = $this.parents('ul');
            var anchor = $this.siblings('a');
            $(anchor.attr('href')).remove();
            $this.parent().remove();

            if ($parent_ul.hasClass('edu-tabs')) {
                $pswp_added_edu_tabs--;

                if ($pswp_added_edu_tabs < $pswp_limit_edu_exp_tabs) {
                    $parent_ul.find('li:last-child').show();
                }

                if ($pswp_added_edu_tabs !== 0) {
                    $parent_ul.find('li:first-child a').click();
                }
            }

            if ($parent_ul.hasClass('exp-tabs')) {
                $pswp_added_exp_tabs--;

                if ($pswp_added_exp_tabs < $pswp_limit_edu_exp_tabs) {
                    $parent_ul.find('li:last-child').show();
                }

                if ($pswp_added_exp_tabs !== 0) {
                    $parent_ul.find('li:first-child a').click();
                }
            }

            //$full_app_form.find(".incremental-nav-tabs li").children('a').first().click();

        });

        // education
        var $new_edu_template = $application_edu_info.find('#new_edu_template').html();
        Mustache.parse($new_edu_template); // optional, speeds up future uses

        $application_edu_info.on('click', '.pswp-add-edu', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $counttab = $(".exp-tabs").children().length - 1;

            if ($counttab != 0) {
                if ($pswp_added_edu_tabs >= $pswp_limit_edu_exp_tabs) {
                    $this.parent('li').hide();
                    alert(prosolObj.limit_cross_edu_tab_msg);
                    return;
                }

                var isOtherTabValid = eduExpTabErrHighlight($jobApplyForm.find('#step1'));
                if (isOtherTabValid == false) {
                    return;
                }

                //check empty fields on all tabs
                var $chkErr = 0;
                $('.edu-tabs li').each(function(idx, elx) {
                    if (idx != $('.edu-tabs li').length - 1) {
                        var $elx = $(elx);
                        var $idtab = $(elx).find('a').attr('href').toString().slice(-1);

                        var cntfield = 0;
                        $('#pswp_edu_' + $idtab).not('div.form-group.hidden').find('.edu-' + $idtab + '[data-required]').each(function(id, el) {
                            var $el = $(el);
                            if ($idtab == 0) {
                                var $chkfoac = $('#activity_selection_wrapper li').length;
                                var $chkbusi = $('#business_selection_wrapper li').length;
                            } else {
                                var $chkfoac = $('.acti_selec_wrap-' + $idtab + ' li').length;
                                var $chkbusi = $('.busi_selec_wrap-' + $idtab + ' li').length;
                            }
                            if ($el.val() != '') {
                                cntfield++;
                            } else if ($chkfoac > 0) {
                                cntfield++;
                            } else if ($chkbusi > 0) {
                                cntfield++;
                            }

                        });
                        //checking
                        //console.log(cntfield);
                        //console.log($('#pswp_edu_'+$idtab).not('div.form-group.hidden').find('.edu-'+$idtab+'[data-required]').length);
                        if (cntfield == 0 || (cntfield != $('#pswp_edu_' + $idtab).not('div.form-group.hidden').find('.edu-' + $idtab + '[data-required]').length)) {
                            $chkErr = 1;
                            return;
                        }
                    }
                });

                if ($chkErr == 1) {
                    alert(prosolObj.all_empty_fields_tab_msg);
                    return;
                }
            }

            var $counter = parseInt($this.attr('data-counter'));
            var $numbertrack = parseInt($this.attr('data-numbertrack'));

            var $rendered = Mustache.render($new_edu_template, {
                increment: ($counter + 1),
                incrementplus: ($counter + 1)
            });

            $activity_selected_ids[$counter + 1] = [];
            $business_selected_ids[$counter + 1] = [];

            $counter++;
            $this.attr('data-counter', $counter);
            $this.attr('data-numbertrack', $numbertrack + 1);

            var $id = $(".edu-tabs").children().length; //think about it ;)
            var $tab_id = 'pswp_edu_' + $counter;

            $(this).closest('li').before('<li><a href="#' + $tab_id + '">' + prosolObj.education + $numbertrack + '</a><span>x</span></li>');

            var $new_tab_content = '<div class="tab-pane" id="' + $tab_id + '">' + $rendered + '</div>';
            $('.edu-tab-content').append($new_tab_content);

            $('.edu-tabs li:nth-child(' + $id + ') a').click();

            $pswp_added_edu_tabs++;

            if ($pswp_added_edu_tabs >= $pswp_limit_edu_exp_tabs) {
                $this.parent('li').hide();
                // alert(prosolObj.limit_cross_edu_tab_msg);
                // return;
            }

            $('#' + $tab_id).find(".prosolwpclient-chosen-select").chosen({ width: '100%' }).on('change', function() {

                var ID = $(this).attr("id");
                var $select_id_clean = ID.replace(/[^\w]/g, '_');

                if (!$(this).valid()) {
                    $('#' + $select_id_clean + "_chosen a").addClass("input-validation-error");
                } else {
                    $('#' + $select_id_clean + "_chosen a").removeClass("input-validation-error");
                }
            });
        });

        // experience
        var $new_exp_template = $application_exp_info.find('#new_exp_template').html();
        Mustache.parse($new_exp_template); // optional, speeds up future uses

        $application_exp_info.on('click', '.pswp-add-exp', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $counttab = $(".exp-tabs").children().length - 1;

            if ($counttab != 0) {
                if ($pswp_added_exp_tabs >= $pswp_limit_edu_exp_tabs) {
                    $this.parent('li').hide();
                    alert(prosolObj.limit_cross_exp_tab_msg);
                    return;
                }

                var isOtherTabValid = eduExpTabErrHighlight($jobApplyForm.find('div.stepDetails fieldset.application-info-experience'));
                if (isOtherTabValid == false) {
                    return;
                }

                //check empty fields on all tabs
                var $chkErr = 0;
                $('.exp-tabs li').each(function(idx, elx) {
                    if (idx != $('.exp-tabs li').length - 1) {
                        var $elx = $(elx);
                        var $idtab = $(elx).find('a').attr('href').toString().slice(-1);

                        var cntfield = 0;
                        $('#pswp_exp_' + $idtab).not('div.form-group.hidden').find('.exp-' + $idtab + '[data-required]').each(function(id, el) {
                            var $el = $(el);
                            if ($el.val() != '') {
                                cntfield++;
                            }
                        });
                        if (cntfield == 0 || (cntfield != $('#pswp_exp_' + $idtab).not('div.form-group.hidden').find('.exp-' + $idtab + '[data-required]').length)) {
                            $chkErr = 1;
                            return;
                        }
                    }
                });

                if ($chkErr == 1) {
                    alert(prosolObj.all_empty_fields_tab_msg);
                    return;
                }
            }

            var $counter = parseInt($this.attr('data-counter'));
            var $numbertrack = parseInt($this.attr('data-numbertrack'));

            var $rendered = Mustache.render($new_exp_template, {
                increment: ($counter + 1),
                incrementplus: ($counter + 1)
            });

            $counter++;
            $this.attr('data-counter', $counter);
            $this.attr('data-numbertrack', $numbertrack + 1);

            var $id = $(".exp-tabs").children().length; //think about it ;)
            var $tab_id = 'pswp_exp_' + $counter;

            $(this).closest('li').before('<li><a href="#' + $tab_id + '">' + prosolObj.experience + $numbertrack + '</a><span>x</span></li>');

            var $new_tab_content = '<div class="tab-pane" id="' + $tab_id + '">' + $rendered + '</div>';
            $('.exp-tab-content').append($new_tab_content);

            $('.exp-tabs li:nth-child(' + $id + ') a').click();

            $pswp_added_exp_tabs++;

            if ($pswp_added_exp_tabs >= $pswp_limit_edu_exp_tabs) {
                $this.parent('li').hide();
                //alert(prosolObj.limit_cross_exp_tab_msg);
                //return;
            }

            $('#' + $tab_id).find(".prosolwpclient-chosen-select").chosen({ width: '100%' }).on('change', function() {

                var ID = $(this).attr("id");
                var $select_id_clean = ID.replace(/[^\w]/g, '_');

                if (!$(this).valid()) {
                    $('#' + $select_id_clean + "_chosen a").addClass("input-validation-error");
                } else {
                    $('#' + $select_id_clean + "_chosen a").removeClass("input-validation-error");
                }
            });
        });

        // expertise section
        // language rating radio checked will show delete icon
        var $pswp_expertise_table = $application_expertise_info.find('.pswp-expertise-table');
        $pswp_expertise_table.find('input[type=radio]').on('change', function() {
            var $this = $(this);
            var $skillid = $this.data('skillid');
            var $skillgroupid = $this.data('skillgroupid');

            var $trash_col = $this.parents('tr');
            $trash_col.find('#skill_' + $skillid + '_' + $skillgroupid).css('display', 'block');
        });

        var $trash_deleted_skill = [];
        var $deleted_trash_check = [];
        var $array_skill_group = [];
        var $array_knowledge = [];
        var $array_classification = [];
        var $array_skillid = [];
        var $array_skillgroupid = [];
        var $array_rating = [];
        var $array_skill_id_deleted_from_popup = [];
        var $array_skill_group_id_deleted_from_popup = [];
        var $array_knowledge_type_deleted_from_popup = [];

        // language rating on click delete icon will hide icon and reset radio
        $(".expertise-btn-modal").on('click', function() {
            $(".content_tbody").find('.trash-expertise-entry').css('display', 'none');
            $(".pswp-expertise-table input[type=radio]:checked").each(function() {
                var $temp_arr = [];
                $temp_arr.push($(this).data('skillid'));
                $temp_arr.push($(this).data('skillgroupid'));
                $temp_arr.push($(this).data('knowledge_type'));
                $temp_arr.push("no");
                $array_knowledge_type_deleted_from_popup.push($temp_arr);

                $('#skill_' + $(this).data('skillid') + '_' + $(this).data('skillgroupid')).css('display', 'block');
                //$array_knowledge_type_deleted_from_popup.push({name: $(this).data('knowledge_type'), index: $skillid_popup});
            });

            // console.log($array_knowledge_type_deleted_from_popup);

        });

        // add default value for skill		
        $('.vskill').on('change', function() {
            var $this = $(this);
            var $skillgr_id = $this.data('sgrid');
            var $skill_id = $this.data('sid');
            var $skillrate_val = $this.val();
            var $skillgr_name = $this.data('sgrname');
            var $skill_name = $this.data('sname');
            var $skillrate_name = $('.vskill[data-sid="' + $skill_id + '"][data-sgrid="' + $skillgr_id + '"] option:selected').text();
            var $array_rate_deleted_from_popup = [];

            //add trash icon	
            var $tempo_arr = [];
            $tempo_arr.push($this.data('sid'));
            $tempo_arr.push($this.data('sgrid'));
            $tempo_arr.push($skillrate_name);
            $array_rate_deleted_from_popup.push($tempo_arr);
            $('#skill_' + $this.data('sid') + '_' + $this.data('sgrid')).css('display', 'block');
            if ($.inArray($skill_id, $array_skillid) == '-1') {
                //add new skill details
                $array_skill_group.push($skillgr_name);
                $array_knowledge.push($skill_name);
                $array_classification.push($skillrate_name);
                $array_skillid.push($skill_id);
                $array_skillgroupid.push($skillgr_id);
                $array_rating.push($skillrate_val);
            } else {
                //update skill details
                var $key_id_exist = $array_skillid.indexOf($skill_id);
                $array_classification[$key_id_exist] = $skillrate_name;
                $array_rating[$key_id_exist] = $skillrate_val;
            }

            //set no value = remove skill
            if ($skillrate_val == 'x') {
                if ($.inArray($skill_id, $array_skillid) != '-1') {
                    var $key_id_deleted = $array_skillid.indexOf($skill_id);
                    $array_skillid.splice($key_id_deleted, 1);
                    $array_skill_group.splice($key_id_deleted, 1);
                    $array_knowledge.splice($key_id_deleted, 1);
                    $array_classification.splice($key_id_deleted, 1);
                    $array_skillgroupid.splice($key_id_deleted, 1);
                    $array_rating.splice($key_id_deleted, 1);

                }
            }
            renderMustache();

        });

        $pswp_expertise_table.on('click', '.trash-expertise-entry', function() {
            var $this = $(this);
            $this.css('display', 'none');

            var $skillid = $this.data('skillid');
            var $skillgroupid = $this.data('skillgroupid');
            $.each($array_knowledge_type_deleted_from_popup, function(index, value) {
                if (value[0] == $skillid && value[1] == $skillgroupid) {
                    value[3] = 'yes';
                }
            });


            var $trash_col = $this.parents('tr');
            $trash_col.find('input:radio[name=skill_' + $skillid + ']').each(function() {
                $(this).prop('checked', false);
            });


        });

        var $expertise_template = $application_expertise_info.find('#pswp_expertise_template').html();
        Mustache.parse($expertise_template); // optional, speeds up future uses

        //var $rendered = '';
        var $counter12 = 0;
        // add new expertise entry
        //save
        $application_expertise_info.find('.expertise-save-btn').on('click', function(e) {
            e.preventDefault();

            mustacheRenderingFunction();

        });

        //abort
        $application_expertise_info.find('.expertise-abort-btn').on('click', function(e) {
            e.preventDefault();

            mustacheRenderingFunctionAbort();

        });

        $application_expertise_info.find('#expertiseModal').on('hidden.bs.modal', function(e) {
            // mustacheRenderingFunction();
        });

        function mustacheRenderingFunctionAbort() {
            //console.log($array_knowledge_type_deleted_from_popup);
            //$(".content_tbody").find("input[type=radio]").prop("checked",false);
            //$(".content_tbody").find('.trash-expertise-entry').css('display', 'none');
            $.each($array_knowledge_type_deleted_from_popup, function(index, value) {
                $(".content_tbody").find('#skill_' + value[0] + '_' + value[1]).css('display', 'block');
                $(".content_tbody").find("input[data-skillid='" + value[0] + "'][data-knowledge_type='" + value[2] + "']").prop('checked', true);
            });
            $array_knowledge_type_deleted_from_popup = [];

        }

        // common function to add new expertise entry
        function mustacheRenderingFunction() {
            var $first_rendered = '';
            var $second_rendered = '';
            var $notsame_rendred = '';
            var $deleted_rendred = '';
            $(".pswp-expertise-table input[type=radio]:checked").each(function() {
                var $this = $(this);
                var $skillid = $this.data('skillid');
                var $skillgroupid = $this.data('skillgroupid');
                var $rating = $this.val();
                var $skill_name = $this.data('skill_group_name');
                var $knowledge_name = $this.data('knowledge');
                var $knowledge_type = $this.data('knowledge_type');

                if ($.inArray($skillid, $array_skillid) == '-1') {
                    //add new skill details
                    $array_skill_group.push($skill_name);
                    $array_knowledge.push($knowledge_name);
                    $array_classification.push($knowledge_type);
                    $array_skillid.push($skillid);
                    $array_skillgroupid.push($skillgroupid);
                    $array_rating.push($rating);

                } else {
                    //update skill details
                    var $key_id_exist = $array_skillid.indexOf($skillid);
                    $array_classification[$key_id_exist] = $knowledge_type;

                }


            });
            //console.log($array_knowledge_type_deleted_from_popup);
            $.each($array_knowledge_type_deleted_from_popup, function(index, value) {
                //console.log($.inArray(value[0],$array_skillid));
                //console.log(value[0]);
                if ($.inArray(value[0], $array_skillid) != '-1' && value[3] == 'yes') {
                    var $key_id_deleted = $array_skillid.indexOf(value[0]);
                    //arr.splice(arr.indexOf("def"), 1);
                    $array_skillid.splice($key_id_deleted, 1);
                    $array_skill_group.splice($key_id_deleted, 1);
                    $array_knowledge.splice($key_id_deleted, 1);
                    $array_classification.splice($key_id_deleted, 1);
                    $array_skillgroupid.splice($key_id_deleted, 1);
                    $array_rating.splice($key_id_deleted, 1);
                }
            });

            renderMustache();
            $array_knowledge_type_deleted_from_popup = [];

        }

        function renderMustache() {
            var $rendered = '';
            $.each($array_skillid, function(index, value) {
                $rendered += Mustache.render($expertise_template, {
                    skill_group: $array_skill_group[index],
                    knowledge: $array_knowledge[index],
                    classification: $array_classification[index],
                    skillid: value,
                    skillgroupid: $array_skillgroupid[index],
                    rating: $array_rating[index],
                    increment: $counter12,
                });
                $counter12++;
            });
            $application_expertise_info.find("#pswp_expertise_wrapper").html($rendered);
        }

        function delete_mustacheRenderingFunction() {

        }

        // delete newly added expertise entry
        $application_expertise_info.find('#pswp_expertise_wrapper').on('click', '.trash-expertise-row', function(e) {
            var $this = $(this);
            var $skillid = $this.data('skillid');
            var $skillgroupid = $this.data('skillgroupid');

            var confirmation = confirm(prosolObj.edu_entry_delete_alart_msg);
            if (confirmation === true) {
                $('select[name=vskill' + $skillid + ']').val("x");
                $('select[name=vskill' + $skillid + ']').trigger("chosen:updated");
                $this.parents('tr').fadeOut("slow", function() {
                    $(this).remove();
                });

                $pswp_expertise_table.find('input:radio[name=skill_' + $skillid + ']').each(function() {
                    var $this = $(this);
                    $this.prop('checked', false);

                    var $trash_col = $this.parents('tr');
                    $trash_col.find('#skill_' + $skillid + '_' + $skillgroupid).css('display', 'none');



                });
                //delete from all arrays
                if ($.inArray($skillid, $array_skillid) != '-1') {
                    var $key_id = $array_skillid.indexOf($skillid);
                    //arr.splice(arr.indexOf("def"), 1);
                    $array_skillid.splice($key_id, 1);
                    $array_skill_group.splice($key_id, 1);
                    $array_knowledge.splice($key_id, 1);
                    $array_classification.splice($key_id, 1);
                    $array_skillgroupid.splice($key_id, 1);
                    $array_rating.splice($key_id, 1);
                }
                renderMustache();
            }
        });

        // attachment section
        var $attachmentModal = $('#attachmentModal');

        // $application_side_info.on('click', '.attachment-btn-modal', function() {
        //     var $this = $('this');
        //     var $parent = $application_side_info.find('.attachment-btn-modal');

        //     var $uploaded_size = parseInt($parent.attr('data-uploaded-size'));
        //     if ($uploaded_size > 10000) {
        //       //  alert(prosolObj.max_total_file_size_exceed_alert);
        //         return false;
        //     }
        // });

        // push mustache attachment content in modal
        var $attachment_modal_template = $attachmentModal.find('#attachment_modal_template').html();
        Mustache.parse($attachment_modal_template); // optional, speeds up future uses

        //attachment modal on show method
        $attachmentModal.on('show.bs.modal', function(e) {
            $attachmentModal.find('#attachmentModalContent').html($attachment_modal_template);

            var $attachmentModalForm = $attachmentModal.find('.attachmentModalForm');

            $('.new-attach-wrap').each(function(i, obj) {
                var typetemp1 = $(this).children().next().next().next().next().text(),
                    typetemp2 = $(this).children().next().next().next().text(),
                    type = typetemp2.replace(typetemp1, "");

                if (type === 'Photo') {
                    $('.pswp-attach-type[value="photo"]').attr('disabled', 'disabled');
                }
            });

            //file upload type change will change textbox value
            var $pswp_title = $attachmentModalForm.find('sidetitle').val();
            // var $accept_file_types = /^application\/(pdf|msword)$|^doc$|^docx$/i;
            var $accept_file_types = /(\.|\/)(pdf|doc|docx|xls|xlsx|txt|odt|ods|odp|rtf|pps|ppt|pptx|ppsx|vcf|msg|eml|ogg|mp3|wav|wma|asf|mov|avi|mpg|mpeg|mp4|wmf|3g2|3gp|png|jpg|jpeg|gif|bmp|tif|tiff|key|numbers|pages)$/i;
            $attachmentModalForm.on('change', '.pswp-attach-type', function() {
                var $this = $(this);
                if ($this.val() === 'photo') {
                    $accept_file_types = /(\.|\/)(gif|jpe?g|png)$/i;
                }
                if ($this.val() === 'docu') {
                    $accept_file_types = /(\.|\/)(pdf|doc|docx|xls|xlsx|txt|odt|ods|odp|rtf|pps|ppt|pptx|ppsx|vcf|msg|eml|ogg|mp3|wav|wma|asf|mov|avi|mpg|mpeg|mp4|wmf|3g2|3gp|png|jpg|jpeg|gif|bmp|tif|tiff|key|numbers|pages)$/i;
                }

                var $parent = $attachmentModalForm.find('.pswp-side-title');

                if (!$parent.is('[readonly]')) {
                    $pswp_title = $parent.val();
                }

                var $new_title = $pswp_title;

                var $readonly = false;
                if ($this.val() === 'photo') {
                    $readonly = true;
                    $new_title = prosolObj.photo;

                    $attachmentModalForm.find('.allowed-file-ext').text(prosolObj.photo_ext);
                } else {
                    $attachmentModalForm.find('.allowed-file-ext').text(prosolObj.file_ext);
                }

                $parent.val($new_title).prop('readonly', $readonly);
            });

            var $attachmentFileUpload = $attachmentModalForm.find('.attachmentFileUpload');

            var $total_to_upload_bytes = 0;
            //binds to onchange event of your input field
            // $attachmentModalForm.find('.attachmentFileUpload').bind('change', function() {
            //     var $to_upload_bytes = parseInt(this.files[0].size);
            //     var $this = $('this');
            //     var $parent = $application_side_info.find('.attachment-btn-modal');

            //     var $uploaded_size = parseInt($parent.attr('data-uploaded-size'));
            //     $total_to_upload_bytes = $uploaded_size + $to_upload_bytes;
            //     console.log(total_to_upload_bytes);
            // });


            //new plugin for upload file https://www.dropzone.dev/
            Dropzone.options.myDropzone = {
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 2,


                init: function() {
                    var myDropzone = this;

                    // First change the button to actually tell Dropzone to process the queue.
                    this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                        // Make sure that the form isn't actually being sent.
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();
                    });

                    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                    // of the sending event because uploadMultiple is set to true.
                    this.on("sendingmultiple", function() {
                        // Gets triggered when the form is actually being sent.
                        // Hide the success button or the complete form.
                    });
                    this.on("successmultiple", function(files, response) {
                        // Gets triggered when the files have successfully been sent.
                        // Redirect user or notify of success.
                    });
                    this.on("errormultiple", function(files, response) {
                        // Gets triggered when there was an error sending the files.
                        // Maybe show form again, and notify user of error
                    });
                }

            }

            // handle attachment file upload
            $attachmentModalForm.find('.attachmentFileUpload').off('click').on('click', function() {
                var $this = $(this);
                $this.unbind('fileuploadadd');
                $this.unbind('fileuploadprocessalways');
                $this.unbind('fileuploadprogressall');
                $this.unbind('fileuploaddone');
                $this.unbind('fileuploadfail');
                $this.unbind('remove');

                blueimpFileUploadCallback($this);
            });

            function blueimpFileUploadCallback($this) {

                $this.fileupload({
                        url: prosolObj.ajaxurl,
                        formData: {
                            action: "proSol_fileUploadProcess",
                            security: prosolObj.nonce,
                        },
                        dataType: 'json',

                        acceptFileTypes: $accept_file_types,
                        // acceptFileTypes: /^application\/(pdf|msword)$|^doc$|^docx$/i,
                        autoUpload: true,
                        maxFileSize: 2097152, // 10MB
                        // Enable image resizing, except for Android and Opera,
                        // which actually support image resizing, but fail to
                        // send Blob objects via XHR requests:

                    }).on('fileuploadadd', function(e, data) {
                        if ($('#attachment_file_upload').find('.attachment-file-upload').length > 0) {
                            $('#attachment_file_upload').find('.attachment-file-upload').remove();
                        }


                        if ($total_to_upload_bytes > 10000) {
                            var $attachment_btn_modal = $application_side_info.find('.attachment-btn-modal');
                            var $uploaded_size = parseInt($attachment_btn_modal.attr('data-uploaded-size'));

                            alert(prosolObj.max_total_file_size_exceed_alert + 10000 - $uploaded_size);
                            return false;
                        }
                        data.context = $('<div class="attachment-file-upload"/>').appendTo('#attachment_file_upload');

                        $.each(data.files, function(index, file) {

                            var node = $('<p/>');
                            var node_btn = $('<span/>');

                            if (!index) {
                                // node_btn
                                // .append(deleteButton.clone(true).data(data)).append(' ')
                                // .append(downloadButton.clone(true).data(data));
                            }
                            node.appendTo(data.context);
                            node_btn.appendTo(data.context);

                        });

                    }).on('fileuploadprocessalways', function(e, data) {
                        if ($total_to_upload_bytes > 10485760) {
                            return false;
                        }
                        var index = data.index,
                            file = data.files[index],
                            node = $(data.context.children()[index]);
                        if (file.preview) {
                            node
                            // .prepend('<br>')
                                .prepend(file.preview);
                        }
                        if (file.error) {
                            node
                                .html('<br>')
                                .append($('<span class="text-danger"/>').text(file.error));
                        }
                        if (index + 1 === data.files.length) {

                        }
                    }).on('fileuploadprogressall', function(e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#attachment_file_upload_progress .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                    }).on('fileuploaddone', function(e, data) {
                        var $this = $(this);
                        $.each(data.result.files, function(index, file) {

                            if (file.url) {
                                var link = $('<a>')
                                    .prop('href', '#')
                                    .text(file.name);
                                $(data.context.children()[index])
                                    // .wrap(link);
                                    .html(link);

                                var $attachmentThread = $('#attachmentThread');

                                $attachmentThread.find('#attached_file_info').attr('data-name', file.name).attr('data-size', file.size)
                                    .attr('data-newfilename', file.newfilename).attr('data-mime-type', file.type).attr('data-ext', file.extension);

                                $attachmentThread.find('.newfilename').val(file.newfilename);
                                $attachmentThread.find('.uploaded-mime-type').val(file.type);
                                $attachmentThread.find('.uploaded-ext').val(file.extension);
                                $attachmentThread.find('.uploaded-filesize').val(file.size);

                                var $radio_val = $attachmentModal.find('input[name=attachtype]:checked').val();
                                if ($radio_val === 'docu') {
                                    $attachmentModal.find('.pswp-attach-radio-photo').remove();
                                } else if ($radio_val === 'photo') {
                                    $attachmentModal.find('.pswp-attach-radio-docu').remove();
                                }

                            } else if (file.error) {
                                var error = $('<span class="text-danger"/>').text(file.error);
                                $(data.context.children()[index])
                                    .append('<br>')
                                    .append(error);
                            }
                        });
                    }).on('fileuploadfail', function(e, data) {
                        $.each(data.files, function(index) {
                            var error = $('<span class="text-danger"/>').text(prosolObj.file_upload_failed);
                            $(data.context.children()[index])
                                .append('<br>')
                                .append(error);
                        });
                    }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');


            }


            $("#input-711").fileinput({
                showBrowse: true,
                showClose: false,
                showPreview: true,
                theme: "explorer-fa6",
                showCaption: true,
                minFileCount: 1,
                maxFileCount: 1,
                autoReplace: true,
                validateInitialCount: true,
                maxFilePreviewSize: 2000,
                browseOnZoneClick: true,
                dropZoneEnabled: false,
                showUpload: true,
                fileActionSettings: {
                    showRemove: false,
                    showUpload: false,
                    showZoom: true,
                    showDrag: false,
                    showRotate: false,
                },
                uploadUrl: prosolObj.ajaxurl,
                uploadExtraData: function() {
                    return {
                        action: "proSol_fileUploadProcess",
                        security: prosolObj.nonce,
                    };
                },
                //dataType: 'json',
                uploadAsync: false,
                elErrorContainer: '#kartik-file-errors',
                allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'odt', 'ods', 'odp', 'rtf', 'pps', 'ppt', 'pptx', 'ppsx', 'vcf', 'msg', 'eml', 'ogg', 'mp3', 'wav',
                    'wma', 'asf', 'mov', 'avi', 'mpg', 'mpeg', 'mp4', 'wmf', '3g2', '3gp', 'png', 'jpg', 'jpeg', 'gif',
                    'bmp', 'tif', 'tiff', 'key', 'numbers', 'pages'
                ]
            });
            /*.on("filebatchselected", function(event, files) {
                $("#input-711").fileinput("upload");
            })*/
            jQuery('#input-711').on('filebeforeload', function(event, file, index, reader) {
                var $to_upload_bytes = parseInt((file.size) / 1000);

                var $this = $('this');
                var $parent = $application_side_info.find('.attachment-btn-modal');

                var $uploaded_size = parseInt($parent.attr('data-uploaded-size'));
                $total_to_upload_bytes = $uploaded_size + $to_upload_bytes;

                if ($total_to_upload_bytes > 10000) {
                    var $attachment_btn_modal = $application_side_info.find('.attachment-btn-modal');
                    var $uploaded_size = parseInt($attachment_btn_modal.attr('data-uploaded-size'));

                    alert(prosolObj.max_total_file_size_exceed_alert + parseInt(10000 - $uploaded_size));
                    return false;
                }

            });
            // CATCH RESPONSE
            jQuery('#input-711').on('filebatchuploaderror', function(event, data, msg) {
                var form = data.form,
                    files = data.files,
                    extra = data.extra,
                    response = data.response,
                    reader = data.reader;
                var error = $('#kartik-file-errors').text(msg);
            });

            jQuery('#input-711').on('filecleared', function(event, data, msg) {
                var $attachmentThread = $('#attachmentThread');

                $attachmentThread.find('#attached_file_info').attr('data-name', '').attr('data-size', '')
                    .attr('data-newfilename', '').attr('data-mime-type', '').attr('data-ext', '');

                $attachmentThread.find('.newfilename').val('');
                $attachmentThread.find('.uploaded-mime-type').val('');
                $attachmentThread.find('.uploaded-ext').val('');
                $attachmentThread.find('.uploaded-filesize').val('');
            });

            jQuery('#input-711').on('filebatchuploadsuccess', function(event, data, previewId, index) {
                var form = data.form,
                    files = data.files,
                    extra = data.extra,
                    response = data.response,
                    reader = data.reader;
                jQuery.each(response.files, function(index, file) {
                    if (file.url) {
                        // var link = $('<a>')
                        //     .prop('href', '#')
                        //     .text(file.name);
                        //     jQuery(data.context.children()[index])
                        //     // .wrap(link);
                        //     .html(link);

                        var $attachmentThread = $('#attachmentThread');

                        $attachmentThread.find('#attached_file_info').attr('data-name', file.name).attr('data-size', parseInt(file.size / 1000))
                            .attr('data-newfilename', file.newfilename).attr('data-mime-type', file.type).attr('data-ext', file.extension);

                        $attachmentThread.find('.newfilename').val(file.newfilename);
                        $attachmentThread.find('.uploaded-mime-type').val(file.type);
                        $attachmentThread.find('.uploaded-ext').val(file.extension);
                        $attachmentThread.find('.uploaded-filesize').val(parseInt(file.size / 1000));

                        var $radio_val = $attachmentModal.find('input[name=attachtype]:checked').val();
                        if ($radio_val === 'docu') {
                            $attachmentModal.find('.pswp-attach-radio-photo').remove();
                        } else if ($radio_val === 'photo') {
                            $attachmentModal.find('.pswp-attach-radio-docu').remove();
                        }

                    } else if (file.error) {
                        var error = $('#kartik-file-errors').text(file.error);
                    }
                });

            });

            //todo: on modal load we need to push the form html using mustache  then attach the validation
            //work on the form submission here, not in modal close.

            //keep in mind that, suppose you upload file but title and description is not added , now while submitting to api finally if you find any file key in session has
            //entry but no title and description then don't push those file.

            //$.validator.setDefaults({ignore: ":hidden:not(select)"}); //for all select

            var $formvalidator = $attachmentModalForm.validate({
                ignore: [],
                errorPlacement: function(error, element) {
                    error.appendTo(element.parents('.error-msg-show'));
                },
                errorElement: 'p',
                rules: {
                    'sidetitle': {
                        required: true,
                    },
                    'attachtype': {
                        required: true,
                    },
                    'newfilename': {
                        required: true,
                    },
                },
                messages: {
                    sidetitle: {
                        required: prosolObj.sidetitle_required,
                    },
                    type: {
                        required: prosolObj.file_type_required,
                    },
                    newfilename: {
                        required: prosolObj.newfilename_required,
                    }
                }
            });

            //validation done
            $attachmentModalForm.submit(function(e) {
                e.preventDefault();

                var $form = $(this);

                if ($formvalidator.valid()) {
                    var $attached_file_info = $attachmentModal.find('#attached_file_info');
                    $(".attach-modal-btn").prop("disabled", true);

                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: prosolObj.ajaxurl,

                        //data: $(".attachmentModalForm input").serialize() + '&action=file_upload_modal_process',// our data object
                        data: $form.serialize() + '&action=proSol_fileUploadModalProcess' + '&security=' + prosolObj.nonce, // our data object

                        success: function(data) {

                            if ($.isEmptyObject(data.error)) {
                                var $success_data = data.success;
                                var $sidedishesdoc = $('#sidedishesdoc').val();
                                var $rendered = '';

                                var $radio_val = $attachmentModal.find('input[name=attachtype]:checked').val();

                                var $radio_type = '';
                                if ($radio_val === 'docu') {
                                    $radio_type = prosolObj.file;
                                } else if ($radio_val === 'photo') {
                                    $radio_type = prosolObj.photo;
                                }

                                var $byte_size = $attached_file_info.data('size');
                                $rendered += Mustache.render($new_attach_template, {
                                    name: $success_data.name,
                                    description: $success_data.desc,
                                    radio_type: $radio_type,
                                    attach_link: $success_data.attach_link,
                                    main_file_name: $attached_file_info.data('name'),
                                    file_size: $byte_size,
                                    filesizebyte: $byte_size,
                                    new_file_name: $attached_file_info.data('newfilename'),
                                });

                                $application_side_info.find("#new_attach_wrapper").append($rendered);

                                var $attachment_btn_modal = $application_side_info.find('.attachment-btn-modal');
                                var $uploaded_size = parseInt($attachment_btn_modal.attr('data-uploaded-size'));
                                $attachment_btn_modal.attr('data-uploaded-size', $uploaded_size + $byte_size);
                                $sidedishesdoc = parseInt($sidedishesdoc) + parseInt(1);
                                $('#sidedishesdoc').val($sidedishesdoc);
                                $attachmentModal.modal('hide')
                            } else {

                                $.each(data.error, function(key, valueObj) {
                                    $.each(valueObj, function(key2, valueObj2) {
                                        if ($attachmentModalForm.find("#" + key).attr('type') == 'hidden') {
                                            //for hidden field show at top
                                            var error_msg_for_hidden_type = '<p class="alert alert-danger" id="' + key + "-error" + '">' + valueObj2 + '</p>';
                                            $attachmentModalForm.prepend(error_msg_for_hidden_type);
                                        } else {
                                            //for regular field show after field
                                            $attachmentModalForm.find("#" + key).after('<p class="error" id="' + key + "-error" + '">' + valueObj2 + '</p>');
                                        }

                                        if (Object.keys(valueObj).length > 1) {
                                            return false;
                                        }
                                    });
                                });
                            }
                        }
                    });
                } else {
                    return false;
                }
            });
        });

        //attachment modal on hide method
        $attachmentModal.on('hidden.bs.modal', function(e) {
            $attachmentModal.find('.attachmentModalForm').remove();
        });

        var $new_attach_template = $application_side_info.find('#new_attach_template').html();
        Mustache.parse($new_attach_template); // optional, speeds up future uses

        // delete newly added attachment entry
        $application_side_info.on('click', '.trash-attachment', function(e) {
            var $this = $(this);
            var $filename = $this.data('filename');
            var $filesizebyte = parseInt($this.data('filesizebyte'));

            var $confirmation = confirm(prosolObj.file_delete_alart_msg);
            if ($confirmation == true) {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: prosolObj.ajaxurl,
                    data: {
                        action: "proSol_fileDeleteProcess",
                        security: prosolObj.nonce,
                        filename: $filename, //only input
                        filesizebyte: $filesizebyte, //only input
                    },
                    success: function(data) {
                        if (data == 1) {
                            $this.parents('tr').fadeOut("slow", function() {
                                $(this).remove();
                            });
                            var $sidedishesdoc = $('#sidedishesdoc').val();
                            $sidedishesdoc = parseInt($sidedishesdoc) - parseInt(1);
                            if ($sidedishesdoc > 0) {
                                $('#sidedishesdoc').val($sidedishesdoc);
                            } else {
                                $('#sidedishesdoc').val('');
                            }

                            var $attachment_btn_modal = $application_side_info.find('.attachment-btn-modal');
                            var $uploaded_size = parseInt($attachment_btn_modal.attr('data-uploaded-size'));
                            $attachment_btn_modal.attr('data-uploaded-size', $uploaded_size - $filesizebyte);
                        } else {
                            alert(prosolObj.delete_err_msg);
                        }
                    }
                });
            }
        });

        // application submission
        var $jobApplyForm = $('#prosoljobApplyForm');

        // won't be able to select future date from today
        $.validator.addMethod('restrictfuture', function(value, element) {
            value = value.split(".").reverse().join("-");

            //var $today_data = $.datepicker.formatDate("dd.mm.yy", new Date());
            var $today_data = $.datepicker.formatDate("yy-mm-dd", new Date());

            if (value !== '' && new Date(value) > new Date($today_data)) {
                return false;
            } else {
                return true;
            }
        }, prosolObj.futuredate_restrict_msg);

        // won't be able to select previous date from today
        $.validator.addMethod('restrictpast', function(value, element) {
            value = value.split(".").reverse().join("-");

            var $today_data = $.datepicker.formatDate("yy-mm-dd", new Date());

            if (value !== '' && new Date(value) < new Date($today_data)) {
                return false;
            } else {
                return true;
            }
        }, prosolObj.pastdate_restrict_msg);

        // phone
        $.validator.addMethod(
            "regex",
            function(value, element, regexp) {
                var check = false;
                return this.optional(element) || regexp.test(value);
            },
            prosolObj.phone_invalid
        );

        // only accept characters
        $.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z\u00c0-\u017e\s]+$/i.test(value);
        }, prosolObj.letters_only_msg);

        function getAge(dateString) {
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // have to 16 year
        $.validator.addMethod('havetosixteenyear', function(value, element) {
            var $sixteen_passed = 0;

            var $get_age = getAge(value.split(".").reverse().join("/"));
            if ($get_age >= 16) {
                $sixteen_passed = 1;
            }

            if (value !== '' && $sixteen_passed == 0) {
                return false;
            } else {
                return true;
            }
        }, prosolObj.under_sixteen_year_msg);

        // edu start year less or equal to end year
        $.validator.addMethod('startyearlessorequal', function(value, element) {
            var $parent = $('#' + element.id).parents('.edu-form-content');
            var $end_val = $parent.find('.edu-end').val();

            if ($end_val == '') {
                return true;
            } else {
                var $diff = 0;
                $diff = parseInt($end_val) - parseInt(value);

                if ($diff < 0) {
                    return false;
                } else {
                    if ($parent.find('.edu-end').hasClass('error')) {
                        $parent.find('.edu-end').removeClass('error');
                        $parent.find('.edu-end').next('p').remove();
                    }

                    // return jQuery.validator.methods.functionA.call(this, value, element);
                    return true;
                }
            }

        }, prosolObj.startyearlessorequal_msg);

        // edu end year greater or equal to start year
        $.validator.addMethod('endyeargraterorequal', function(value, element) {
            var $parent = $('#' + element.id).parents('.edu-form-content');
            var $start_val = $parent.find('.edu-start').val();

            if ($start_val == '') {
                return true;
            } else {
                var $diff = 0;
                $diff = parseInt(value) - parseInt($start_val);

                if ($diff < 0) {
                    return false;
                } else {
                    if ($parent.find('.edu-start').hasClass('error')) {
                        $parent.find('.edu-start').removeClass('error');
                        $parent.find('.edu-start').next('p').remove();
                    }
                    return true;
                }
            }

        }, prosolObj.endyeargraterorequal_msg);

        // exp start year less or equal to end year
        $.validator.addMethod('expstartyearlessorequal', function(value, element) {
            var $parent = $('#' + element.id).parents('.exp-form-content');
            var $end_val = $parent.find('.exp-end').val();

            if ($end_val == '') {
                return true;
            } else {
                var $diff = 0;
                $diff = parseInt($end_val) - parseInt(value);

                if ($diff < 0) {
                    return false;
                } else {
                    if ($parent.find('.exp-end').hasClass('error')) {
                        $parent.find('.exp-end').removeClass('error');
                        $parent.find('.exp-end').next('p').remove();
                    }
                    return true;
                }
            }

        }, prosolObj.startyearlessorequal_msg);

        // exp end year greater or equal to start year
        $.validator.addMethod('expendyeargraterorequal', function(value, element) {
            var $parent = $('#' + element.id).parents('.exp-form-content');
            var $start_val = $parent.find('.exp-start').val();

            if ($start_val == '') {
                return true;
            } else {
                var $diff = 0;
                $diff = parseInt(value) - parseInt($start_val);

                if ($diff < 0) {
                    return false;
                } else {
                    if ($parent.find('.exp-start').hasClass('error')) {
                        $parent.find('.exp-start').removeClass('error');
                        $parent.find('.exp-start').next('p').remove();
                    }
                    return true;
                }
            }

        }, prosolObj.endyeargraterorequal_msg);

        //for all select

        //choosen select valid check.  source https://stackoverflow.com/questions/12468313/integrating-jquery-validate-and-chosen-js
        $(".prosolwpclient-chosen-select").chosen().on('change', function() {

            var ID = $(this).attr("id");
            var $select_id_clean = ID.replace(/[^\w]/g, '_');

            if (!$(this).valid()) {
                $('#' + $select_id_clean + "_chosen a").addClass("input-validation-error");
            } else {
                $('#' + $select_id_clean + "_chosen a").removeClass("input-validation-error");
            }
        });


        $full_app_form.on('click', '.prosol_errortab', function(e) {
            e.preventDefault();

            var $step_index = parseInt($(this).data('stepindex'));

            $jobApplyForm.formToWizard('GotoStep', $step_index);
        });

        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        };

        var pswp_title_req = $('#pswp-title').prop('required');
        var pswp_federal_req = $('#pswp-federal-state').prop('required');
        var pswp_phone1_req = $('#phone1').prop('required');
        var pswp_mobile_req = $('#pswp-mobile').prop('required');
        var pswp_email_req = $('#pswp-email').prop('required');
        var pswp_nationality_req = $('#nationality').prop('required');
        var pswp_marital_req = $('#maritalID').prop('required');
        var pswp_gender_req = $('#gender').prop('required');
        var pswp_birthcountry_req = $('#birthcountry').prop('required');
        var pswp_availabilitydate_req = $('#availabilitydate').prop('required');
        var pswp_expectedsalary_req = $('#expectedsalary').prop('required');


        var $jobApplyFormValidator = $jobApplyForm.validate({
            ignore: [],
            errorPlacement: function(error, element) {
                error.appendTo(element.parents('.error-msg-show'));
            },
            errorElement: 'p',
            rules: {
                title: { required: pswp_title_req, },
                lastname: { required: true, },
                firstname: { required: true, },
                street: { required: true, },
                zip: {
                    required: true,
                    digits: true,
                    minlength: 4,
                    maxlength: 15,
                },
                city: {
                    required: true,
                    lettersonly: true,
                },
                countryID: { required: true, },
                federalID: { required: pswp_federal_req, },
                birthdate: {
                    required: true,
                    restrictfuture: true,
                    havetosixteenyear: true
                },
                phone1: {
                    required: pswp_phone1_req,
                    regex: /^[\d-+]+$/,
                    minlength: 9,
                },
                phone2: {
                    required: pswp_mobile_req,
                    regex: /^[\d-+]+$/,
                    minlength: 9,
                },
                email: {
                    required: pswp_email_req,
                    email: true,
                },
                nationality: { required: pswp_nationality_req, },
                maritalID: { required: pswp_marital_req, },
                gender: { required: pswp_gender_req, },
                'profession[]': {
                    required: true,
                },
                birthcountry: { required: pswp_birthcountry_req, },
                availabilitydate: {
                    required: pswp_availabilitydate_req,
                    restrictpast: true,
                },
                expectedsalary: {
                    required: pswp_expectedsalary_req,
                    digits: true,
                }
            },
            messages: {
                lastname: { required: prosolObj.lastname_empty, },
                firstname: { required: prosolObj.firstname_empty, },
                street: { required: prosolObj.street_empty, },
                zip: {
                    required: prosolObj.zip_empty,
                    digits: prosolObj.zip_digit,
                    minlength: prosolObj.zip_min,
                    maxlength: prosolObj.zip_max,
                },
                city: { required: prosolObj.city_empty, },
                countryID: { required: prosolObj.countryID_empty, },
                birthdate: { required: prosolObj.birthdate_empty, },
                'profession[]': { required: prosolObj.profession_empty, },
                expectedsalary: { digits: prosolObj.expectedsalary_digit, },
            },
            submitHandler: function(form) {

                $(window).off("beforeunload");
                if ($jobApplyForm.serialize().hasOwnProperty('typeID')) {
                    //do struff

                    return false;
                }
                $full_app_form.find('.prosolapp_submit_msg').html('');
                for (var i = 0; i < 10; i++) {
                    if ($('#pswp-beginning-date-' + i).val() !== '' && $('#pswp-end-date-' + i).val() === '') {
                        $('#pswp-end-date-' + i).remove();
                    }
                }

                for (var i = 0; i < 10; i++) {
                    if ($('#pswp-exp-beginning-date-' + i).val() !== '' && $('#pswp-exp-end-date-' + i).val() === '') {
                        $('#pswp-exp-end-date-' + i).remove();
                    }
                }

                var siteid = getUrlParameter('siteid');
                var anyparam = 0;
                if (siteid) {
                    siteid = '?siteid=' + siteid;
                    anyparam++;
                } else {
                    siteid = '';
                }

                var hassource = getUrlParameter('source');
                if (hassource) {
                    if (anyparam == 0) {
                        hassource = '?source=' + hassource;
                    } else {
                        hassource = '&source=' + hassource;
                    }
                    anyparam++;
                } else {
                    hassource = '';
                }

                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: prosolObj.ajaxurl + siteid + hassource,
                    data: $jobApplyForm.serialize() + '&action=proSol_applicationSubmitProcess' + '&security=' + prosolObj.nonce, // our data object
                    beforeSend: function() {
                        //disable submit, next and prev button
                        $full_app_form.find('.application-submit-btn').prop("disabled", true);
                        $full_app_form.find('.wizard-prev-btn').prop("disabled", true);
                        $full_app_form.find('.wizard-next-btn').prop("disabled", true);
                    },

                    success: function(data) {
                        //var $errorMessages =  data.error;						
                        if (data.ok_to_process == 1) {

                            var $success_data = data.success;
                            var app_submit_msg = '';
                            if ($success_data.hit == 1) {
                                app_submit_msg = '<p class="alert alert-success">' + $success_data.msg + '</p>' +
                                    '<p><a href="' + window.location.href + '" class="btn btn-info" role="button" onClick="window.location.reload()">' + prosolObj.apply_again + '</a></p>';
                                swal($success_data.msg, '', "success");

                                $jobApplyForm.remove();
                                $full_app_form.find('ul#steps').remove();

                                $('#jobModal').remove();
                                $('#activityModal').remove();
                                $('#businessModal').remove();
                                $('#attachmentModal').remove();

                                $('#nace_groups_template').remove();
                                $('#operation_areas_template').remove();

                            } else {
                                //as error , so enabled submit, prev and next button
                                $full_app_form.find('.application-submit-btn').prop("disabled", false);
                                $full_app_form.find('.wizard-prev-btn').prop("disabled", false);
                                $full_app_form.find('.wizard-next-btn').prop("disabled", false);

                                app_submit_msg = '<p class="alert alert-danger">' + $success_data.msg + '</p>';
                            }
                            $full_app_form.find('.prosolapp_submit_msg').html(app_submit_msg);
                        } else {

                            //as error , so enabled submit, prev and next button
                            $full_app_form.find('.application-submit-btn').prop("disabled", false);
                            $full_app_form.find('.wizard-prev-btn').prop("disabled", false);
                            $full_app_form.find('.wizard-next-btn').prop("disabled", false);


                            var $custom_error_message = '<ul>';

                            var $tab_error_ref = data.tab_error_ref;
                            var $tab_names = prosolObj.form_tab_key_names;

                            $.each(data.error, function(key, valueObj) {
                                //for hidden field show at top

                                // if ($jobApplyForm.find("#" + key).attr('type') == 'hidden') {
                                if (key == 'top_errors') {
                                    $.each(valueObj, function(key2, valueObj2) {
                                        if (typeof valueObj2 !== 'object') {
                                            $custom_error_message += '<li>';
                                            $custom_error_message += valueObj2;
                                            if ($tab_error_ref.hasOwnProperty(key2)) {
                                                var $step_index = $tab_error_ref[key2];
                                                $custom_error_message += ' ' + prosolObj.form_tab_key + ' <a class="prosol_errortab" data-stepindex="' + $step_index + '" href="#">' + $tab_names[$step_index] + '</a>';
                                            }

                                            $custom_error_message += '</li>';
                                        } else {
                                            $.each(valueObj2, function(key3, valueObj3) {

                                                $custom_error_message += '<li>';
                                                $custom_error_message += valueObj3;
                                                if ($tab_error_ref.hasOwnProperty(key2)) {
                                                    var $step_index = $tab_error_ref[key2];
                                                    $custom_error_message += ' ' + prosolObj.form_tab_key + ' <a class="prosol_errortab" data-stepindex="' + $step_index + '" href="#">' + $tab_names[$step_index] + '</a>';
                                                }

                                                $custom_error_message += '</li>';
                                            });
                                        }

                                    });
                                } else {
                                    $.each(valueObj, function(key2, valueObj2) {
                                        if (key === 'education' || key === 'experience') {
                                            $.each(valueObj2, function(key3, valueObj3) {
                                                $.each(valueObj3, function(key4, valueObj4) {
                                                    //for regular field show after field by class
                                                    $jobApplyForm.find("." + key + '-' + key3 + '-' + key2).after('<p class="error">' + valueObj4 + '</p>');

                                                    $custom_error_message += '<li>';
                                                    $custom_error_message += valueObj4;
                                                    if ($tab_error_ref[key].hasOwnProperty(key2)) {
                                                        var $step_index = $tab_error_ref[key][key2];
                                                        $custom_error_message += ' ' + prosolObj.form_tab_key + ' <a class="prosol_errortab" data-stepindex="' + $step_index + '" href="#">' + $tab_names[$step_index] + '</a>';
                                                    }

                                                    $custom_error_message += '</li>';
                                                });
                                            });
                                        } else {
                                            $jobApplyForm.find("#" + key).after('<p class="error">' + valueObj2 + '</p>');

                                            $custom_error_message += '<li>';
                                            $custom_error_message += valueObj2;
                                            if ($tab_error_ref.hasOwnProperty(key)) {
                                                var $step_index = $tab_error_ref[key];
                                                $custom_error_message += ' ' + prosolObj.form_tab_key + ' <a class="prosol_errortab" data-stepindex="' + $step_index + '" href="#">' + $tab_names[$step_index] + '</a>';
                                            }

                                            $custom_error_message += '</li>';
                                        }

                                    });
                                }
                            });

                            $custom_error_message += '</ul>';

                            $full_app_form.find('.prosolapp_submit_msg').html('<div class="alert alert-danger">' + $custom_error_message + '</div>');
                        }
                    }
                });
            }
        });

        $(document).find($jobApplyForm).on('keypress', ":input:not(textarea):not([type=submit])", function(e) {
            if (e.keyCode == 13) {
                return false; // prevent the button click from happening
            }
        });



        /**
         * Check if all existing tab are valid, alert error message, return true/false
         * @param step
         * @param tabErrorMsg
         */
        function eduExpTabErrHighlight(step) {
            var step_nav_tabs = step.find('.nav-tabs');
            var tabErrorMsgs = '';
            var tabErrorMsg = prosolObj.invalid_tab_count_msg;
            var stepIsValid = true; //reset tab status

            step.find('.tab-pane').each(function(tab_index, tab_container) {

                var tabIsValid = true; //reset tab status

                $(':input', tab_container).each(function(index) {

                    var xy = $jobApplyFormValidator.element(this);

                    if ($(this).hasClass('prosolwpclient-chosen-select')) {
                        var ID = $(this).next('.chosen-container-single').attr("id");
                        if (!$(this).valid()) {
                            $('#' + ID + " a").addClass("input-validation-error");
                        } else {
                            $('#' + ID + " a").removeClass("input-validation-error");
                        }
                    }

                    stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
                    tabIsValid = tabIsValid && (typeof xy == 'undefined' || xy);

                });

                var currentTab = step_nav_tabs.find('li:eq(' + tab_index + ')');
                if (tabIsValid == false) {
                    currentTab.addClass('error-tab');
                    var tabTitle = currentTab.find('a').text();
                    tabErrorMsgs += tabErrorMsg + ' ' + tabTitle;
                    tabErrorMsgs += '\n';
                } else {
                    currentTab.removeClass('error-tab');
                }


            });

            if (stepIsValid == false) {
                alert(tabErrorMsgs);
            }

            return stepIsValid;
        }

        $jobApplyForm.formToWizard({
            submitButton: 'applicationSubmitBtn',
            nextBtnName: prosolObj.next + ' &gt;&gt;',
            prevBtnName: '&lt;&lt; ' + prosolObj.prev,
            stepStr: prosolObj.stepstr,
            nextBtnClass: 'btn btn-primary btnprosoldes-step next wizard-next-btn',
            prevBtnClass: 'btn btn-default btnprosoldes-step prev wizard-prev-btn',
            buttonTag: 'button',
            showProgress: true,
            showStepNo: true,
            validateBeforeNext: function(form, step) {

                var stepIsValid = true;
                var tabErrorMsgs = '';
                var tabErrorMsg = prosolObj.invalid_tab_count_msg;

                if (step.selector == '#step1' || step.selector == '#step2') {
                    var step_nav_tabs = step.find('.nav-tabs');

                    step.find('.tab-pane').each(function(tab_index, tab_container) {

                        var tabIsValid = true; //reset tab status

                        $(':input', tab_container).each(function(index) {

                            var xy = $jobApplyFormValidator.element(this);

                            if ($(this).hasClass('prosolwpclient-chosen-select')) {
                                var ID = $(this).next('.chosen-container-single').attr("id");
                                if (!$(this).valid()) {
                                    $('#' + ID + " a").addClass("input-validation-error");
                                } else {
                                    $('#' + ID + " a").removeClass("input-validation-error");
                                }
                            }

                            stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
                            tabIsValid = tabIsValid && (typeof xy == 'undefined' || xy);

                        });

                        var currentTab = step_nav_tabs.find('li:eq(' + tab_index + ')');
                        if (tabIsValid == false) {
                            currentTab.addClass('error-tab');
                            var tabTitle = currentTab.find('a').text();
                            tabErrorMsgs += tabErrorMsg + ' ' + tabTitle;
                            tabErrorMsgs += '\n';
                        } else {
                            currentTab.removeClass('error-tab');
                        }


                    });

                    if ((step.selector == '#step1' || step.selector == '#step2') && stepIsValid == false) {
                        alert(tabErrorMsgs);
                    }
                } else {
                    $(':input', step).each(function(index) {

                        var xy = $jobApplyFormValidator.element(this);

                        if ($(this).hasClass('prosolwpclient-chosen-select')) {
                            var ID = $(this).next('.chosen-container-single').attr("id");
                            if (!$(this).valid()) {
                                $('#' + ID + " a").addClass("input-validation-error");
                            } else {
                                $('#' + ID + " a").removeClass("input-validation-error");
                            }
                        }

                        stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
                    });
                }

                return stepIsValid;
            },

        });
    });
})(jQuery);