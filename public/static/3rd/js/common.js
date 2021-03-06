/************************************************ Begin Extend Javascript Object ************************************************/
$.extend(Date.prototype, {
    // returns the day of the year for this date
    getDayOfYear: function() {
        return parseInt((this.getTime() - new Date(this.getFullYear(), 0, 1).getTime()) / 86400000 + 1);
    },
    // return true if is leap year;
    leapYear: function() {
        return ((this.getFullYear() % 400 == 0) || ((this.getFullYear() % 4 == 0) && (this.getFullYear() % 100 != 0)));
    },
    /*Returns the week number for this date.  dowOffset is the day of week the week
     'starts' on for your locale - it can be from 0 to 6. If dowOffset is 1 (Monday),
     the week returned is the ISO 8601 week number.
     @param int dowOffset
     @return int*/
    getWeek: function(dowOffset) {
        dowOffset = typeof (dowOffset) == 'int' ? dowOffset : 0; // default dowOffset to zero
        var newYear = new Date(this.getFullYear(), 0, 1);
        var day = newYear.getDay() - dowOffset; // the day of week the year begins on
        day = (day >= 0 ? day : day + 7);
        var weeknum, daynum = Math.floor((this.getTime() - newYear.getTime() - (this.getTimezoneOffset() - newYear.getTimezoneOffset()) * 60000) / 86400000) + 1;

        if (day < 4) {// if the year starts before the middle of a week
            weeknum = Math.floor((daynum + day - 1) / 7) + 1;
            if (weeknum > 52) {
                nYear = new Date(this.getFullYear() + 1, 0, 1);
                nday = nYear.getDay() - dowOffset;
                nday = nday >= 0 ? nday : nday + 7;
                weeknum = nday < 4 ? 1 : 53; // if the next year starts before the middle of the week, it is week #1 of that year
            }
        }
        else
            weeknum = Math.floor((daynum + day - 1) / 7);

        return weeknum;
    },
    // returns the number of DAYS since the UNIX Epoch - good for comparing the date portion
    getUEDay: function() {
        return parseInt(Math.floor((this.getTime() - this.getTimezoneOffset() * 60000) / 86400000)); // must take into account the local timezone
    },
    isToday: function() {
        var curDate = new Date();
        return (this.getUEDay() == curDate.getUEDay());
    },
    format: function(mask, lang, utc) {
        var arrLanguage = {
            vi: {
                daysSName: ['CN', 'T.2', 'T.3', 'T.4', 'T.5', 'T.6', 'T.7'],
                daysFName: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
                monthsSName: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                monthsFName: ['Tháng một', 'Tháng hai', 'Tháng ba', 'Tháng tư', 'Tháng năm', 'Tháng sáu', 'Tháng bảy', 'Tháng tám', 'Tháng chín', 'Tháng mười', 'Tháng mười một', 'Tháng mười hai'],
                timeMarker: ['s', 'S', 'sa', 'SA', 'c', 'C', 'ch', 'CH']
            },
            en: {
                daysSName: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                daysFName: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                monthsSName: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                monthsFName: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                timeMarker: ['a', 'A', 'am', 'AM', 'p', 'P', 'pm', 'PM']
            }
        };

        var language = arrLanguage[lang];
        var masks = {
            'default': 'dddd dd mmm yyyy hh:MM:ss TT', // Chủ nhật 20 Tháng 10 2008 12:37:21 CH
            shortDate: 'd/m/yy', // 20/10/08
            mediumDate: 'd mmm, yyyy', // 20 Tháng 10, 2008
            longDate: 'd mmmm, yyyy', // 20 Tháng mười, 2008
            fullDate: 'dddd, d mmmm, yyyy', // Chủ nhật, 20 Tháng mười, 2008
            shortTime: 'h:MM TT', // 5:46 CH
            mediumTime: 'h:MM:ss TT', // 5:46:21 CH
            longTime: 'h:MM:ss TT Z', // 5:46:21 CH EST
            isoDate: 'yyyy-mm-dd', // 2008-10-20
            isoTime: 'HH:MM:ss', // 17:46:21
            isoDateTime: 'yyyy-mm-dd"T"HH:MM:ss', // 2008-10-20T17:46:21
            isoUtcDateTime: 'UTC:yyyy-mm-dd"T"HH:MM:ss"Z"' // 2008-10-20T22:46:21Z
        };

        var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g;
        timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g;
        timezoneClip = /[^-+\dA-Z]/g;
        pad = function(val, len) {
            val = String(val);
            len = len || 2;
            while (val.length < len)
                val = '0' + val;
            return val;
        };

        // Passing date through Date applies Date.parse, if necessary
        date = this;
        if (isNaN(date)) {
            throw new SyntaxError('invalid date');
        }

        mask = String(masks[mask] || mask || masks['default']);

        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == 'UTC:') {
            mask = mask.slice(4);
            utc = true;
        }

        var _ = utc ? 'getUTC' : 'get',
            d = date[_ + 'Date'](),
            D = date[_ + 'Day'](),
            m = date[_ + 'Month'](),
            y = date[_ + 'FullYear'](),
            H = date[_ + 'Hours'](),
            M = date[_ + 'Minutes'](),
            s = date[_ + 'Seconds'](),
            L = date[_ + 'Milliseconds'](),
            o = utc ? 0 : date.getTimezoneOffset(),
            flags = {
                d: d, // Day of the month as digits; no leading zero for single-digit days, ex: 1.
                dd: pad(d), // Day of the month as digits; leading zero for single-digit days, ex: 01.
                ddd: language.daysSName[D], // Day of the week as a three-letter abbreviation, ex: CN.
                dddd: language.daysFName[D], // Day of the week as its full name, ex: Chủ nhật.
                m: m + 1, // Month as digits; no leading zero for single-digit months, ex: 1.
                mm: pad(m + 1), // Month as digits; leading zero for single-digit months, ex: 01.
                mmm: language.monthsSName[m], // Month as a three-letter abbreviation, ex: Tháng 1.
                mmmm: language.monthsFName[m], // Month as its full name, ex: Tháng một.
                yy: String(y).slice(2), // Year as last two digits; leading zero for years less than 10, ex: 99.
                yyyy: y, // Year represented by four digits, ex: 1999.
                h: H % 12 || 12, // Hours; no leading zero for single-digit hours (12-hour clock), ex: 1.
                hh: pad(H % 12 || 12), // Hours; leading zero for single-digit hours (12-hour clock), ex: 01.
                H: H, // Hours; no leading zero for single-digit hours (24-hour clock), ex: 15.
                HH: pad(H), // Hours; leading zero for single-digit hours (24-hour clock), ex: 24.
                M: M, // Minutes; no leading zero for single-digit minutes. Uppercase M unlike CF timeFormat's m to avoid conflict with months, ex: 1.
                MM: pad(M), // Minutes; leading zero for single-digit minutes. Uppercase MM unlike CF timeFormat's mm to avoid conflict with months, ex: 01.
                s: s, // Seconds; no leading zero for single-digit seconds, ex: 1.
                ss: pad(s), // Seconds; leading zero for single-digit seconds, ex: 01.
                l: pad(L, 3), // Milliseconds; gives 3 digits, ex: 100.
                L: pad(L > 99 ? Math.round(L / 10) : L), // Milliseconds; gives 2 digits, ex: 88.
                t: H < 12 ? language.timeMarker[0] : language.timeMarker[4], // Lowercase, single-character time marker string. No equivalent in CF, ex: s or c.
                tt: H < 12 ? language.timeMarker[2] : language.timeMarker[6], // Lowercase, two-character time marker string, ex: sa or ch.
                T: H < 12 ? language.timeMarker[1] : language.timeMarker[5], // Uppercase, single-character time marker string. Uppercase T unlike CF's t to allow for user-specified casing, ex: S or C.
                TT: H < 12 ? language.timeMarker[3] : language.timeMarker[7], // Uppercase, two-character time marker string. Uppercase TT unlike CF's tt to allow for user-specified casing, ex: SA or CH.
                Z: utc ? 'UTC' : (String(date).match(timezone) || ['']).pop().replace(timezoneClip, ''), // US timezone abbreviation, e.g. EST or MDT. With non-US timezones or in the Opera browser, the GMT/UTC offset is returned, e.g. GMT-0500 No equivalent in CF.
                o: (o > 0 ? '-' : '+') + pad(Math.floor(Math.abs(o) / 60) + Math.abs(o) % 60, 1), // GMT/UTC timezone offset, e.g. -0500 or +0230. No equivalent in CF.
                S: ['th', 'st', 'nd', 'rd'][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10] // The date's ordinal suffix (st, nd, rd, or th). Works well with d. No equivalent in CF.
            };

        return mask.replace(token, function($0) {
            return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
        });
    }
});

$.extend(String.prototype, {
    stripTags: function(allowed) {
        allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
        var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        return this.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
    },
    truncate: function(length, truncation) {
        truncation = truncation ? truncation : '...';

        if (typeof (length) == 'string') {
            truncation = length;
            length = 20;
        }

        return this.length > length ? this.slice(0, length - truncation.length) + truncation : String(this);
    },
    blank: function() {
        return /^\s*$/.test(this || ' ');
    },
    empty: function() {
        return this === '';
    },
    left: function(n) {
        if (n <= 0)
            return '';
        else
        if (n > this.length)
            return this;
        else
            return this.substring(0, n);
    },
    right: function(n) {
        if (n <= 0)
            return '';
        else
        if (n > this.length)
            return this;
        else {
            var iLen = this.length;
            return this.substring(iLen, iLen - n);
        }
    },
    mid: function(star, n) {
        return n ? this.substr(star, n) : this.substr(star);
    },
    getAS: function(s) {
        return this.substr(0, this.search(s));
    },
    getBS: function(s) {
        return this.substr(this.search(s) + 1);
    },
    trim: function() {
        var reg = new RegExp('(^(\\s|' + String.fromCharCode(12288) + ')*)|((\\s|' + String.fromCharCode(12288) + ')*$)', 'g');
        return this.replace(reg, '');
    },
    getNum: function() {
        var nums = '0123456789';
        var result = '';
        for (var i = 0; i < this.length; i++)
            if (nums.indexOf(this.charAt(i)) >= 0)
                result += this.charAt(i);
        return parseInt(result, 10);
    },
    slug: function() {
        var str = this.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, '-').replace(/-+-/g, '-').replace(/^\-+|\-+$/g, '').toLowerCase();
        var from = 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđñç';
        var to = 'aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydnc';

        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from[i], 'g'), to[i]);
        }

        return str;
    },
    countWords: function() {
        return this.stripTags().trim().split(/\s+/).length;
    },
    ucfirst: function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
});

$.extend(Array.prototype, {
    blank: function() {
        return (this.length === 0);
    },
    empty: function() {
        for (var i = 0; i <= this.length; i++) {
            this.shift();
        }
    },
    removeDuplicates: function(interator) {
        for (var i = 0; i < this.length; i++) {
            for (var j = this.length - 1; j > i; j--) {
                if ((interator && interator(this[i], this[j])) || this[i] == this[j]) {
                    this.splice(j, 1);
                }
            }
        }
    },
    swap: function(i, j) {
        var temp = this[i];
        this[i] = this[j];
        this[j] = temp;
    },
    in_array: function(value) {
        return this.indexOf(value) > -1;
    }
});

var Common = {
    url: {},
    device_env: 3,
    arrLanguage: [],
    rndStr: function(length) {
        var str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var value = '';
        for (var i = 0; i < length; i++)
            value += str.charAt(Math.floor(Math.random() * str.length));
        return value || null;
    },
    loadCaptcha: function(link) {
        $('#refresh_captcha').on('click', function() {
            $.ajax({
                method: 'GET',
                url: link,
                success: function(response) {
                    $('#captcha_image').prop('src', response);
                }
            });
        });
    }
};