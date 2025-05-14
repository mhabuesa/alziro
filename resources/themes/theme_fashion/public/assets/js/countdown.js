!(function (e) {
	e.fn.countdown = function (t, n) {
		var o = e.extend(
			{
				date: null,
				offset: null,
				day: "Day",
				days: "Days",
				hour: "Hour",
				hours: "Hours",
				minute: "Minute",
				minutes: "Minutes",
				second: "Second",
				seconds: "Seconds",
			},
			t
		);
		o.date || e.error("Date is not defined."),
			Date.parse(o.date) ||
				e.error(
					"Incorrect date format, it should look like this, 12/24/2012 12:00:00."
				);
		var r = this,
			i = setInterval(function () {
				var e =
					new Date(o.date) -
					(function () {
						var e = new Date(),
							t = e.getTime() + 6e4 * e.getTimezoneOffset();
						return new Date(t + 36e5 * o.offset);
					})();
				if (e < 0)
					return (
						clearInterval(i), void (n && "function" == typeof n && n())
					);
				var t = 36e5,
					s = Math.floor(e / 864e5),
					d = Math.floor((e % 864e5) / t),
					a = Math.floor((e % t) / 6e4),
					f = Math.floor((e % 6e4) / 1e3),
					u = 1 === s ? o.day : o.days,
					l = 1 === d ? o.hour : o.hours,
					c = 1 === a ? o.minute : o.minutes,
					h = 1 === f ? o.second : o.seconds;
				(s = 2 <= String(s).length ? s : "0" + s),
					(d = 2 <= String(d).length ? d : "0" + d),
					(a = 2 <= String(a).length ? a : "0" + a),
					(f = 2 <= String(f).length ? f : "0" + f),
					r.find(".days").text(s),
					r.find(".hours").text(d),
					r.find(".minutes").text(a),
					r.find(".seconds").text(f),
					r.find(".days_text").text(u),
					r.find(".hours_text").text(l),
					r.find(".minutes_text").text(c),
					r.find(".seconds_text").text(h);
			}, 1e3);
	};
})(jQuery);
