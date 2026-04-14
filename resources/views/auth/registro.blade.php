<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone — Crear cuenta</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--green-900);
            display: flex;
            min-height: 100vh;
        }

        /* ── Panel izquierdo ── */
        .auth-panel-left {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .auth-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/webp;base64,UklGRoA8AABXRUJQVlA4IHQ8AADQEgGdASqTAQ4BPp1Em0olo6MiKhLtILATiWVsG9cRyGEaQi9Jim88sQbLljeg/0vfX+c/dvKU/Y8kfbrqZW2P87wX/XfEU/Yf316o8OJw7fh4Y8ffh7/nPUW8oj/y86v71/5TLv6ooX9n5Wgvx1OK73LPkL7cvT3WAIwX303u+vcVAx1m7kq6XlhBP0jHlwg//dsNvo917wjMK1OD/Cp/F/7XU0oc+03t18Tp0YOrc0J0FyncOrKs8xLyEZpmqw7MXm7ujOLZ4rSffgx3ms+NapLTkLT3w+C8Wg9DSr4YrRUSB/n3gDb3wjPFV2Ooeo0SD2qjZouysbTB7url0FDv5h7hv1NxJtGcooIq2yZZG1Qeh6acsmAKotB41Zc5RdLzjjqtpS8+feQqKXM0db4Z7mUh0jRcJbFFxA6yoSQuJJxvIieXwI/26d3x0stiOLnq5lchdH1c8yyeVePgIC5Cs3RJDpaO24GMmjXdWUd5BCf1aQPIUTXdjnyeAroNdBgPWzQc8Qbuq4o0YY8RyfxDClBkW4rslkZ17WwN7kjrXKlpqFp7fsgKy9MO4Beg20qYxFUa81VnJWJMNaJGtxT5YLtd4phVHIbWAGzuWjsm8UgDkxEqoYVW+rjhzu72IshGpyNZX1u0WMJlRzSShR2O7KP0Cw709VkURH/z+86zOXBfZbyqGGObpKYjY7h/bsJfpHdygfAv2JyfVfDefFFJDIHQ+xDoeyvcRGB3cMMe8JXVUdPJR4hEHU7+i18266O/6ixv9Mb2byWJ9XnQihBb8klOLaD3+TQEJ3vsstYNZxSOCdY1Aw+z9anIEnDHORVTO+Ag5xm4Ua7B9HcjpUs9h8zETkWK+pmtVgl+LJGrtwUOCjb5hrXL0MUEft2/lYyu7rd+3AevU95Cb/Lvn08RtY9MwVp3BB9nFff2R/7iu0FHbDB0ckxmsJuv/j8HtKdSqGivpN8fMljuPQSpkiwXBw+62O1y0ON2U//YcXTA7fOAzolvl/s3zZQ81G3LnKpX4X9U2vlcBHjagQXCDEgEot+tT+aMUHpzt/UZeqYNZmEqhLcidluq78Ffuz8i1atvN+NYefkw3VImogSEmycZraL5mWelwWLT69dwaHN73DeDCK3JxdlQVFONBkqUY2F/pFJDGee8fy0WZPDOgVhKyqv+wgaX7rx8cwQ3R2ss55nl7iI5fW057l8AtZcqtTP5hHnyEdjVTBQfgmrn8nh5L0EMHKU8+EwYPxO7aHkIpFgnZwAa5uC2+xavz12O7goL2x+Vf9IE5ts8W3njz4UR4TohtL+zlOzB28qeK/Qx4S+v83dTVHanXl+TLz7NN83xYUf9mDVxDGLkprkZScm3bAxI3dFYi5hnVsbQUOSOHCVoXvgn33214XBdQhIuNqGPQHRWBbz4uj/hGNW+hQUkw8vtgJLWP8qxtLl4Uww3+2rjkGQFk8BQwLHfFkelbl/wucrgt+eHlVWmWeLRaIH9QGiDpprwAeRUMKMeBietZeLoQjlxt7o7b1/8x7osFSaFrg1AoafivFcV7zSULH7QVKvf+tiTKwIq8K1K0QHbDkA10I3b3m2IzzWWRqQ5tGOPrhtGrKCG9yq4xOr4CbYZQBC4d094znkiCdbdL+qdTgygQXFVJaftJ1C/KwMv1Vm0enQtVa7Ag8TtzDrLo3XIR3VZalsxB9ReEBwYtdY98o4byu6PsxikwORwhMBnwagHRv3qY8T6t94KWtOJyo2+FAXTYe7v/gFTLVeRLzcWQte1UkQk0B2s5fnN+2CAHArEfrYgrqlWBxxjVA6WDamyID2NT65Qbl4rvEfDSrMGFwf/PRGurKnScEmCFoOmnIskwDWfv8sovonFtPif9woviCZ3Euk8irq6xORAG+2l7oGC/kHojLjLhS1y0sFtaanYQD+ckIKpGTOLvRKJvIOZSmP2e3iymV28lfRk5dAWG4WD3z5rRTZYkEd/D2J7ZdPsEEOoau+ebR9yf3IIaj6dypC9JCksgh1JkPY3RT6deD2PuAUoeS1khfdYeZNGE3ef8VCPnFXXoKcRzCwoa0IUr3cF7iz2Qwb1QLH0LUFFdK611E10jjEkITwkk7MetlkZUYT1ddfuLfmnDU1oTxjqR1qWgzMZlncNOTXBy14C8lrfrA40ci3bR8CA8S8SrLpNsc12t/A6z9voPtegpyk0BgePkms3lFIPO7l3gJKPeYlrtkj9tf7eB7RriuQr+QplZjIgT75TuokA+7ubtnQdmOoZdQFrlWrvtuJkkJvPJ0b3kEbiym2gO/WsW4H9fSflfrgxO1Fa2f5Z+GjlI8URfvgvUgZQcvUfven2ZYxXPfKEBuVL8eMnjPLlgL+1jDO6rX0UHtesxFpR/9CrNYjhqo1rfRmJUC3C3CyWAEnTvA7TLMi2+uBhQvGIoy+8frcdWYakRUJDi/WJfImfVonxD3NQVCIIhqNddMR0MfUrdZfk8wy5omDTSwtHa0Detnj4Y0WzI8q9WOxqe3OQAtUmVuZMT4LpS+jSE8SEowfHPWfknGqhltKdjPeYnhxLqPR0KNUNdNeZd+rXB/a3sTH9ScCM9xYL9ZeFpExx1etH3bFdHEczQZjJRYd0EIBzaodoPu6IU5EJvgRjnD7giyJktfsuLiX9icvw4Wnnwc+aCX4a/OGGfACVFWRWuyehHUT8DRt1hFnDBmUloNFjDUp2GH1nckFfQxf5b5XiY6Sjxu1yY3DhCsPsG0GOApbORQzxh8ugw7UTzvtbafcfOc88GH7CL/m80PyZj0eGqVdL61frlx80/kmHL2H+jfq0T5yQouACeOVp007tc7FJO9BYdjvhHzfYzaZBOZ2hYf1bOncncdj83Urd/9c3A40V32Fp8z62umaGA+e6iLZkTI8IM6mMr9S8prMiFEw9WK9UBcnmTVJp5BEuMNIF2BP3oAD++RTdtpeDyShjllDBqtvbNnPQbUJkxJTlrqmRsLw66W9KTIxZ0NwjFRtnxKjkoBdGcAAqT+4H8QpY40/j7vdL2wBDMTUxoAPIwkA3p5zq34o2nziYiVhcqp+hHzw9liHAw9Vjp0Yp1NfN4nSrT9Z+jWI3lOvMO3V3cQzc2LrZDvPue2JLxov3YV8ebM1dsRFnMrhLCDaroTJhpGzZNprMZ/Q/p9m6NvTcD6b42GCPzZwRNIdMjstvMCiEdgbNuPX3gi3t4TM7gu+4y/QryPLfb/jn0CNVeiaqck623xefJpm1S2dBGXH7604bq5uPn5Qc29aDMKqbnycSXm19c8tncz+eUqIN4gwdzpcXvuMKsFGbEdBxS4EtDQIstK0Hz8/7QLbOA7kfJQlnBycK896UzkfjyRH5QafjqLcmzD0wKlRZ6Pgk9D5+qufYMdjl0XOHtbjcHmipgElpU/dH8TYNMSZXlw6mKXJtt7DGLt3xJBa6A3ut5Q8sA/QrIhhCD7VGvL8L1yeJmy+jZ0UAbfetk+ToliCjcG9vFjHUAISBNyKouvFo8auREjo6dGtcjPFDnpFPGL2yz3NrgTfv0OzFOYX0DhPsylfcjGePq517BqgVwkLjGop6KFbHsRqV+2Xzilb5ubrVVRvjoQijnSHGfnH5rzaqzkl2u3i7HjaCa8Yq7MWtfbh2LCnC9P78IXDSK3atgVX8pZk/oCdBJeWsglCGSuEAAS1ay6Nsro49cvKkg5lNgt0O937OEm1kQuuxSNn3gjffrpty7chBrYaDl1rlCPr/DRc33XQbLjHZNcfHY0/yTdjOVp6xzHlWi+DZ2URtmHS/+i5CzgQIQ4HonRi+0rwCkOIzt7+Bn70kj5SkigtOoP3DSCr2LjkMc7QoInYY49COVhSwG9yS8iuj9EBnXuToH4xewtvVEcZBaPHInxBUQbBUC4GARS7htTkXWwHAcBVJWgLx5WdsMln/Map2ZuydHkRSH4Hv+iZTwmlyYKzE99NzsB9NeoNYbqSBeamc2p4bkgLYpx/bv7EMFgfSk1rqWC5U6szdHAIBpp5t2fKFqb27GUzUt3ZorRC80SKvTGa0X5DuKPJj3KDI+L2tK9OVn39BvKQP5GRwOGQECOCmvmgtV91rph8QqTLtgAwP+ZhPeIqSfymFVafYXpuUBhWO4lfJzER4HMVAuYp1NuC89jPTReL4lnBQZ6s6Ycds9SP5KPPhhULmfkGOyrjpT3XCSUou5Af8fG3fXAW9zw7OMgFeKi6LwR3IhFxEi6cy2ZSc5tySkeLmmdrmkvNPapKbKFUT420eek5dqQvKNxhckW5WVVmvk6QpZwianJIjcE2sKr0jLvTNxg2aGfW+LimdI3hhrfCFXGMUyqcWLMIeMcqlPx0tYdFSG2RYH0l102ICZLa40t08yyzfUlQ+47+XWf+MwogmnNBamVTvrqyIb+evIXDAT4wCp3vz4BYtkU6PzQKvrMSuxbiz0bqBAi2tyD7QG8TMahAeoRri+A8ige/Q22pnaXnmUeWlQ78heP0k1xEDCzvzT2jWpMczLBL43Bb85pbuGCBNQFs6tRLvloGYFYqItTV15ej93a4q5muOfbPOy+LQLuc/EPsM04VWd2AgqWu5LlmuNZd3nAQmCUCgQEl68gtlv8fmwFo/d5iWTn5XkvAQXn3n70dhSZA/Vzq77+cAEoaZ+FoHdLX89NBfJK986FH03tqyng7ePJwTnf4y0kF3fr7cJPhzWmE48teJ/TLHjpPyfFm7lJbgNKs0NhPZ48lGKiPWZb4LqU6NpxHxlcbUAhthmC607Pja+kFpX1IH3m9xoE14Tt1glzv8t7Cs71Z+8URXOe6kt07/cBer3ttM8h61nK+Mhb3F1nhlN35u+T22/4eFDKtDvjhPMI5kDLam0HcAI2pwuQ/TisG6aBBh/sSoZ0l6cDExQl/gCR9byKpwV67CfCG62jDDe7ZJGHMPSXAr7lcxyiC7ne/8p5F+/LLKRwQcUAlVZA3qDpVY8WunR8fPSv2dDcoLNsLAU0ft1sBD/mt2aD1Ge+hMpb2V3L+3p9qIWS+7wyQha7up7YH49J4DqqrIeKc5A67T/pZ0q1Q6hLRNltxoV0DhJXzPegP4AEUtZtmjRFvS+fFP1cJsdJLtp3xHwT00cAQmBBX601v37OCSSx1PUDGWeawEVHAex64G0DeogyKZukba2kvKfkUPdrCBmWpcLCxjlZ4USXWZCngUnjh8am2/EjEbR92p6yCJ16Sw8q3D0EPRJV8+BH4uoKN4arGVxxqymMbkbZI6+klBZkDT5n1SPUHho7bRBG8LML66aIHoZObVlJrqhcyF4z6ITgWCKYSCFffSAj1c1fxvn8SzhcNpXTo2ZKNCMzH7rJCL2Go4n2iRI6AJWsB2goetlhOVGxIiSQ88ZZsha7+esODVes+IYO4xg2uRDDhGbrh5CtURSbnI2hjeyTbcpAwteqZBhG47hzh6FDWB6e8GxHPuL4yD65aeoVICNlGK0ZW45iMlAtUdFxtV54+wtaN0LvT9J99t/rtcKM6OdGo1YoZidHZ0HayQziKwsiIVTKjkFsdmVjLxotOYdlc/u9QWj+n7B8y2QQ9mjnXd1pg25dZb5ja3V4/h50I9ghmJmyB8AYRFFx5xt89ytHhuPBsC8j/lYA4NyHE2020bn2Ch2eqee99tCLR6gPsLp6ypkwBALptXu7MEivO8RH6V/2YY7WvhncqyG288gL1dS1VJYGQZ9ytOB9WLVq9fOdgC5y8HUz1MW66HMWF8SlSfK0ctKHl5QhMWaacbKfLYZW3kqf0zF+xysUsSfbzr+Ns4QH65sQHBxvyXgcpY5Ike+kqa9xfa0Nj7L3CNqclKdOVlQZsmLnQOIInIrRwdxaybBBS73eIZmkcWrrsD0GDTYnuIAiK1QY0AHs46yQjtb3vnpd72SNCJRvqZMiuSFfaKM+VRuJcDme4o3Lgh/zDWgz2DWNLxp56C+bx5zKMAPsoekBuR5u+bTY3HlPFMuuegiSZU1JMmcFj1Alqj0qBMjcYfu2JIARa8DD+aJlidE0XUt/i/q/oycCmokH2pVbRsgl2NB8jkBlaq5glE0TDQNseI9PHsRcLe+rLhuiLI4NvznjUbIQn42voJtnJ2EgIh2CPHoDce4t3jduTHhLcWae9+vyWSR+f4L+QRVorm2Dj8V1o4Zhrb2L5JWpY25L+9yfymecMLLDPyOpn09HT0JEpwhGIeNdRbzlqIqf/z8yv7Lx9fOjM3kX/184fVmNxFORUJoikoNA48CwXS3JY4exX7drWfII0u+lx+d9pcqPqTg3h8uS0yOSwiiSDk+40EWaRJrjXF9y6h/PgGsnmjbSlq17JUIjnf2Vn5ouiVbjRsAqkyse+Zn8obqYBvYWL7dcLvD/kiDpoSGnsVxqjfHwcCkei3weUnORHEHLLQWZ7JE9srNyKr5yXM7S324isRKwqZ2giE2yjeiBM9y3qR1PQ2ayKNKAD/pCtlUnDAYKdpqTdBX7WbpMrn+Lth4LryqkKdT1BzFoZ2Z9fZk32+mxh/E7tub/RFjDFoDW51W0Qi6xzrv+G0OZ6GKfvZlD56LdsQKWuVOOeEf4GGGuUhUnZKqacl9485viBuUoBxcU/dl0nsdG3D9bBor3Nks2Z0qxwzcdFA2izoJBtdSpbPi2vx2vHK5gPFTkv/u7ZYoiW74I6Qstn/+LMtSBbl/JJZRKKZ7KyrA43aJDexu4+wy7gOwA4lvAcCx4Zxs4urnBlMgwIG/km5iLR6KGmAz1ZZ9Y23ajW1hfcn8O4raMTccNFlnDaxQ/V+mMMHNr44iW2w/deREhD9oSd/DZdJENWwAX+17rIbPUDVobB9405ktLqA0rwqs1fEEEnDmhgLmcxPgi0HVJLxdo0F6Tz43wC7jciXFtZe8p7kHXA74b30Q1HPlxjW/WWmmVX4wcWIfsbXSFxED0AxqM8bHFAm3afvVfgO0kzXfSOSzfnobhxpJEy8ZBEtWl11SzMTxZtAC54iyAJ4mawbJr5pQB3qw/C1TkuOgUsraZN8txq1lvQB6SOvSx0k6lSvPjacgb5K+CvJPziNAHvcVtAYFblEjOXi/yXc1sC7ZZz0kQdtxzQzXlKysVEH9mO3nx5J9ZJnhgpDDuKJzbYbEELOw7DwhMO1oaMww0lVBcS2CKcbJXBrvbzn43+ozcWrxrGFefD9tUxG61TBY4gI/Frh1vooQO70fC08vsTFxDkB+KkPMGv+4v4JxQ9rbwtBPP6lIR7Y5tCfctfCdH6RfhOURHwPCrx7J63pMTHk/MbqcM6se1N5pNmVDx3Mi4wQsOhTDWnakOGZfGP925vZi81bY5NT9W/Abs0P1z5DzeC9IrB40i+tevgqrkkkjnGo+TviS+mErb3+0E9HIHTCaZ4HLcj+SCj6hHBKA8RZseHhQaryjeuYNjv0Rn2bEmdCZg/mnTnGcWB1zZcCTzreBC1bc6PZEXOahzMEPzv9bbq+vco6/U2vM7PQsemRXLqyHcXViyyb0F44ueOQ+DBgj0PmN2xlhAuwrHyi5BsAjY2qXMyt47zbUr+VJGyz+7RG2jY18QB7jA8Wvq3NiXE85h+Fy4h6PPK21LUyI/RhyK8zEbAneFbhnuENms0gRND5Qcm9GFzs3rhvD/Z0Xu6bahbH6vOHa8LNxnQe1fy9uDvCopFcwvo/eyunTIS5GyncpLxI0zDp4K3ldXCGp+lB6ovFx+0J2gse+4LiyqoFwGY8IONypq4ynEWBNfhCGXQp/utif7vt01jki8DTJdsdfvzGTxF45nXh7TB5mSnOM9kJ1TFFLr7+aEba8Wh14gFWH00rdupkKKgnXgmhUfibWW37cGSlHzMYeERJV/YNDXeodhR4PikYuxqUumThQqEnuXWaFRZnISalc05yPG+SdXxHfwtUGBKzhaeUdRir8cFbsVutWsBbV5q0rDZrQB4znqpWKOqiy1Pmzj/gIyiv6LZtfVRVIBwmYeS34v8dzKksNjqrDT9eNr1NHQPDMfAgWLqa8Ynp8NyK5KhGiza5Aaate79Ac2p3YNJni0FuU4QuJA9LkBd6wfwEGAlGFoAPHwE7muaj73SGcPAwEqccCPE2xRJjN7fZsUqQFF4UnqWx3s6JvSuqBTveMr0Eleyn6NasGNM37O1YSQj1FpY/9+X1+Lv+AQWsSzWrEuEfybIS88o6+UiJmJSjoB73LtRxuKaOcsWJMAt4o6f11zgzY0b/XoT9AizBQJB2KwW4rFzJRlVmzgyAWQ5vAsAooD4GRu2gjuXgTYXEef8L1GXO02sEpN+7fTPIWCvXZGD77IkWvf4sb5ksj00+E6zB4lGja56xVD+2XI2vhjgaW+dlkfuikgOKGvu51UM69fRObZZJYjfVM/9/rv6UGKiANZtQWXCKqXnL2PWX3ZnPrv/kCzu0+AuqsYfnZPDVXHPmtDKENq5V8n7w05jncrFwE2eBbK9fE+1HOGvBJSTfeWfWCd3WysnnHpG4PzGPOLK6pnu8f87ylM1RWN7xIEgCLh3XFUyieQTqtaeZySx3PcuvhRpjfEoWYbfpfJlH3Hm8iCBAsMYXXIhyRnS4iq/tj/cT/9B2MRKLXgt2TaZnUmzfcP1x9t4n7DXeZibjSoJulMfZDRKeMFZakfH03L3+Ptq9HRHT77/yb0JChvrs16yK4f8/pfaA7gVqy8GgxRu/kYOw/ARLacOgovwJELzqsIbE3mvRPxYL2ZfU1bITWC9VZ2XAvJBr2uFXPlUIAb0MW19z2xkVJgG8/n7Qn8rBpT6HGWXwGaSJRqAaieFksaDYdw1hOf+dGmWmo7fz8YUEc2K7yH0qQhxnlwHf2TXLaHbzzQr2V5iXw+tG21a4OoyVlRcHH5vX7b/yqLmXWOujcoQn/dWqyzWsvLSFzZ2An/kNZ58OcQfpXMdqpK6HRcKVmkPKGHDUuUb375aTlKmEaCD+QI7lj0UveyKaZmLA5qjelw9SHNcoHKmf4rZ3/hBQrbd8hhp6rp7rXVusvGCFAvCm7yOz03FC7AsxMvEYVzwQB+ADTn65TZTFCr0ZUhwoQqffcuWGjEHwa8+f7GU+Nbg381aK2iMK17wKq11o0EDpL7FPnSMVsiTVl9M/V41xVMQr0f0Eebyj5OG2li0cHbdtUYWGIelZVNZEj9C0rseGLwOi72iFmqP675B+QAwtGe25rtqnvvZCU3D0h/H5RK+cuUw1SSJdxHZYcjFKF71tC3u9BEibQTyYhM+xctYDvJASgWVL54tShd84Xo8oOZeJ/fPu4I8p5klBo0soeoQ7NFajFP0M5ZercdXEY11+l6Mg1oIjNLX3r+aKh9hRMblhsWNEQPofixZcDmYDzxww3nLJ6H5siYYFFUVHvHiWmhGi1DUatEO3W08YIq7E/eiBE+NTqtRFe8Vb97tSCXBYqKCIOJSg8e7DaQ/eJ71wdze9Q1Gy4GpVtsrCNc34B9W8glBY9HKmJVCW+gIJjPY55Twf+HqHSkHN5XVhaBKv2iwtfbVhmdlzKbeStTUX5t0W3HT7UGRvghnrTRLSnZshE7e9xDqFHhzdw9ULHQCmkvxnO78+wqe4O3rOCzIeMYb/9q04BmieKH2JMZ3BGGRN+tgIoAxGk3WpQxqS+BBVbjZZbu8t22deFbXI4akjWIMMd7Fn/n7oW2YOPdvMlqSvqxP6G8PV8OdM0gqWT7z46YtAt6zYkAiU0vh8Vr5E2+H5+SYNkE6LnqLL2miUzgmPodkIlDPxroRDrb9cjDXJbfdJDH8W89QvYNmdf6rbxvL2fry0xUZoPODxXRLBJ/MYboBQWeYnyhyRGaxB2UyGY/by6lMUpCe6VZxhTENDibLzeErikIM+1kyTaoDQzrxcXLr1CsksesQgs59GZs2HtaC8JleOosBmFinye8Uk5ZmtRIXTwDIokwLMY+zsvEKO1uBD/WF/DsLQO/WElXQJx0op5pXIX0uww2/5MmHSVMgOMeBoad1/LACrLbAXUED8qW3qYL6cPGEsqiIL/5678o0stChasqH3LOHtUOAS8GTGKdpF5WqrEI4AIwXojY3hO3cxb3BVFcGlUMuYxzvH7x/Z7l25k0/2JlzbA9ewL7EKSqNiB8LeBlMsjV/vZKf4gIo52Y5RQ1W0HlfEy6qQ2g246VVoQL5P4meaiLS80FyaRpPeHIzWB+yiXx0wZaX1RTXdyA9WiTAmWeCcdplI0wxjwX76mAspjKSGM7wu6wlO4FynQDKUfTbRksTPz6g72ygalxzAeJlEEY4f/GUhde8sp5OxxOLp8EXZbL/QiaAwhd5/PzeAtHNfpV55ORcwVnNpy6gJab81ZB0G4U+MmiAPZlE1mfOeWwGyfk6Vi4p4hAQkrwgV8s7PQWgwO0gz+RbE4rGMNyrWdsIhUglVGVggaykQYDqLaA+lvcyGxaomIfBIZXX8AA6bafhbs5GFSkDB1g9azOkOcaRcowVS92EaBWFD1t4BT4wwHhD2fE5BIVPSEar6Gu25uZZD8r9NgJd1XGzMGklEjhQnHDAltolh0AY6zzi6aeNSiW2Q8NK1ePYCNxhTsb0ZObwiOJFD5DcLRDz77kGr5dYvrDMnz4WbySwDlnguaPeXt5nBNo8CZZAFt9mgvX6mIqcwjYhPkZF/LPS7JlaBW/2iGmMuOyqTV7oSOco7LJ9hbz7OT8m/qUQhWUikfwRsepJ4ZyMuNykwjNzTcbuUh7vfYjUSOK8gpbyhhMmSeO+02UtKKTXEGCcAWrfjeBjYFETT/Pipne1svDD1RtNyTDUuVEnzt0hldrcc/yyRbRObOAwHHR7AOtCGeUtxYAggEmnWhiR10O6KN715xyGX/d1PcWcMNLuU1V40PvjsiRo4JpvF9aCIFYwNtvfX8ZrZcnenwnaXPEd/jBgZEvEs/W0oYCy0yAi5ASYq2YTa7/FRpd7T4o9UXK5UNMPZRUyfj/hAWl5Cs2VZNbKfFo48mTbKpebQl+23FKqxp/Dy/czrMtW/ej/0Uk1nAOLu9wgz9exsQvrf41UPeLriXmx2V0qegakfcJc+Aw1yk1P1CY3AJkM8DIhNPEBrlHcCHnbvQIkwL2uckgXz+fSiB9A3h3E+nvNhWFtMxpAPXq4mQuYM4uJU90lqHVU6kIEfzqoKRzO6daw3BDBtNP75ZhaoIiMxITg06h/zI+qtl7cq25rcO2Coh3VvPUkoLeOs0B4xedrqPFCZYZtJoQK3UFBa3tRPTN2X/y3AWlJI8sE224OTPiDc7m9RQVDlAn/1g/+cIerpk/qcd+mbfis7MWUN0yllt/cZ3sNfx1NxTcgu7h3VhluuBT64HTvd/eZ8cuqDUOWTpO/c/y3Lvsi4SvMkGfw0uw8dAESeEBQZnPb4llZ59x7GkcmUAJPyM9OsLso76kCtT39YA/2eGvWHURmtVNMHzrMysKWuHbORL/FlwvqZyR42tD9/MAKpw184JVSPkTpqdEud44xnq1QNMMzhmO0xfJunggLD1kbU3DIBK4HfHiW9XajGOJZeXxvNtN2RCm4FR5yev46++2ujbxlTe+3MjpZSgRrGFz66mQmOw4ZyyC2hxUU1+6KnzIHvHVV2yCnaNKnoL9d2frLaDSn8vAM1cmNJXz/EPkpKuTRrHfZx4NvyngcwCv0Bj8FvDVTSj7MxKLOxSrMAbXjp4Qhijn2b+qjoCF8/BRiL3Mm48h+GVyE/c/8ARjKm8kMNgFBGVkhwOdw07SjLdjacr2mT9J7RUIgrI2pRwxPvM/XvSaczgeZe/Q93JmOC2EXNaYTYa6llRZQvtTIPAQ00PDPe9PM91MKXMBF6A9oMohfXf4iESFuym/nUeeZdRIGRDyVjinQxN+QTD2/Ba+eyqrMUdfObUAiw+skJOI4YBWzsDxnMRJ8YYUdoQYbybet7zPPocUeJkVb9w4uejzlBRB8gWkSNeqqGy/K68ZKHR5sMfJPa0L/SXd4FNvlCRI7i3hr8zrNS1i7kB/OMlCZfN/UxLU/VDOitzou6b8FOJhrJkV/crZnfIQzV1CVouVgVZJTeoj7kfQ+NmC+tC5B2r7kl26FMqBo1hdA1GYSwYhVFqsoT/hrqXb6LqCkRKb25oXQYBN5fyTA5kBBb9hDAqb8cJo4zgxSbBcMIb5ohG9iKBOS7detKBl8vKNOCddTH+1TGokT4vHo8bCVc9A2IcDISkD2yOF1t2FSw805l1Bii7f5WHFRIeFCbFAr+BUaMDVW8fEfA/ywnztNuaX0PzF+jir3dctFjeVDKUKWzeOYCGi5i7OKJqZwbAszk12SbSpbzf0fGiFJcHPA+l+I0+MzGv+3UOgWciCtIR+qOOpmCsYMNWpRYMttG7LoTP+wjPb+KMwoCH0ig+Za9tA+CrwgICdFJpZ1ybdkHPXniG9W709wzuewLwczUtUXYtK604e4NtXHifjwc9CNKQSTk9wdr/FcQtZJ6PY+MN4jVgE8EKAsz7gYAvvb17hZDiQpY+SaCG0AprGPhW7IIMa/5mOpovICvifQOlZBcXu/GzRo+ChYJaqU2lFFS26oZ7uYIXQorK/SeixvFtflIXPGmmh3MUuk+ys/xmwSMPjPDBguTZSqvJ+Cfr9ZsKfKf+NBEQNx2dMm5zs58Ntw24tSm6n1gZZPQaKHhlTsw3P6wajvFIq7Ws1DXRquvmPFhfEC0tTV9X3L+dS8QeCowy3SXFxMPW/8sN4sO7jIdKUxJ/X+qiHjgDC+QqNuflXC5/UOxwl1b3BCZX97qxIqVmC3T/SEBULiYl8OlEKq0YHDFjK3bY3B++KZQKyERCysDNsbvRAtc9cSdmi/3MkHPeU9SFjIR5kfiG49T7QCYoFCBDDnxBCS/o17h+oIzhpDKECtxZ+VH7io9xqDLq3MM+LIA0Rvls7dEcOKz8ja4zj27HCFLxL+EkIwu4QMpPUI9xTACdcvxgDo5OdrTk0p7PBbH3Q2vocp5TA9pHK9iPM5c/GR78fmi5fUzD7sUjKeWIIfNJ7O5YICCAJbSq12IxjF/vKNu7BSgQKZQ6QXfMaoyEL8YqPGj9Qy2qzlzqdXwqCp2cW9b/aOoqL+kVUZK7uwXauWhZCv6lhLvyYUbYfhxQ/43vYaPhtIfRN811xzy1/aHr+GzBolV4ghslHLLyxflacqRtroAi2spTvjkrc+bh7TBik9cH7q5W12KlNZhryw5DRDKInsRCdpI5wsv+uxMYb+f6AEWsdpU3zSjZtUWZkrgkuusNsFQsYSFTQ5mxDOMnAnHRjy0dL7UaUsxE9lIC0vXqDX2Ln/tzBgFUESR0Ct4ZG9pWyP7k7XvtQI8TNZdFCR+NxpvI42qpSL2khYqpC3hieJOeOH/uvot9rlBK0HAjksEFHZMKUQpWG4rDQsmH4FQlfN1Nvsbz4IwOtrqYBL2wqWZxvT+49e158Qhj0ThmqWa4xeJU6Pki+IewSyxYIMEWmIqKSRm65gGpigXlaW8vw/MgvaxEJxhRN4En+up+baI134nnKDdvIQVC4xr0KlIUQv1erTm0s0yqfldBNKAbBn5t6Fk5WZCMrU5wF4pAufa498UbxgARepHaew8OV+NDZBFexWkS/3rTDZVWxNHhPjPZvDBogG/fPB4Xmxf/E5utCei13YVa0U+g8n89RW0dn4p6X7CrPooOZT2z/GSHBa/sz2zfui+icAB09I29ALU6lnLJ2futK/WgfA14kXIUKTzrNd71JP7RkhxE5Rx+vy8h3FhXMGSHnmOOpBfss1qCLkQHzxFxXPANoqhHdtWluR9S3XtOroJ3iWD7GDwJ3BJzUsN6itbm9Tlahov6RZa3DtdphoImDi+eP9pdrNIIae5wtQNygXjGigulvwUAdhULGqGx1XaPspIIJ9d0darxbW3O07qNBj1pN0zI1i1BDbyqri2Zalhl2S3/iGa/X7j5TYp/vjeIZm56b8zLfNBn9fMnwa/Sc3X/MmrEqdND1QlMs/OuhYNklKJlEtgRgUvaPek7z0iBBhSSbnhm35j/I6vy70HZO3thCImVx+F55pdyJue8owDDs9S+dxJjEYByB1kXhnQCswGWSJv0+IiYmIUJRa3Pau3mxXSoEOXpiE4rSudJK4vKZy6+PC4X/iGYn9S7YZkL24pQJLbT4B69Uy4aXm5mwayx9Opgp/5Nj8t0hjJDNVe8n1FUsr78cLfSgUWHytQIHYVSi3zhi7dO7iIMr+SShZ4FocxR0vt/KnDZKWXMcaWIP3YBA16JBvyxqFqLuDPqSOkJiUyXkELmvHJpwj7EQY1C5g75TKKPnZyK/pdHB0ZVO+MLo19+gzPfH5oofabkncESRQf2kaNGMS+5vgDloDVj/njgx5LcAOFqJbuBQhf9bVNJP/UNP9DN6vz5k0XTrfg+t7EKP0aMMfCAsCUsIrU03+EwXa7kdGzCJhwi6r7aLkGyw2Gvh2q815skJJm/MA7a5TY/JnBYPMwCpQW8FjS/ihwf9XLan1juOkJ8K2AW33XtKHK+cogcO6TAWlPEjo0gaonYtwBYpsFQQjvZlzGlkZ7pctdyG6Xe0zE07ZL3jO1PSNdnTfuim0yJNBJs9Ot68HYEyvTbAkSdCBRaZtTV1eybg5Icr6UhuiIN+nNAAT9xkALZs+bOVJwbiEuWhBJ+kTmujS0VXk8d/+XItMtFj9ArAFcXvTdAWcuUPJPcY1u4AzVL8AGlvs1niAe2jxzbxCUbIACguNMdc29P79HHAHh0WNqpsRRltG4g3EYezOBehxpBTvLPP7gofu7+jBtU09zr2AZ4Ai+Fep9udDucVMNlhOx7zmT3S2Q07inpgEu8UKRJUhf6+CPMDUMiGmDA32RaHBqrxaS4/Dc44hM0JavN/D4uRsV19OpS2G4kqpHAGD8lHaqBbu9mlbXSsFCL0a1X402jr7/xuVRXzYEjIDRuY/Qnixx4dbJCfogd8nyHvD+7/7qrO3kqOhC/F69j0NX2mZIQyc/z7NEr7g2BqDlLJnvW5z3BT4KEZDY3N6Se5gox6JS3Pi1UykuIW218an5jrnE/9xsgtk6gbPnFnhn0bwlGiRBA7xHFGWrLLtVkYYyR9aTf5O1x1zs2lQlt4nwF09JyCeWGEJhd+lzs35LOx+OjNqM3lNyvUa991M+aGDO5DQvaM0PZ+sT1VXlUxMvQPxTuaPSyZjx3Rxpqt1s1E1I3i5hh2NdGxuoikPpqhlki65uj05ehlJMX8JLTKuFzsRJ8K/X6vN3Bnv7Adan3q/cx/GlbLEWIQDW6YMhLGtNBOdQX90o+tFlN2X/cjjIbA2bUNvjzbFKgfPGIMJgSZ218KvSWQ1oeO0hkS1yX1zr4OSAfGiF6ZHRXbdmzXaOig3Qr888LPeGGl9sGbr4fN844ILu0Ah4QYd+d3YNjRvV/j8vPCk4rYc3cVnh6GhQSRVKXjYGBovMEGem4gb1NP9o1R9nTfBM4M/Uf0OgNw4+eeGYqr+PcLycMp+YbiSFaM3n06PdGzxR2zbA6u8wWnA4AYNbxUxuCMRiSUzuL6xbNaLsmmkyCbQ7x/mVxxsaJw+nFaVgLxC58j0ss8Lu28LFRkiDgPgHYg+TujDoPDspIwvkUM6ZmMdilQBZNEvNmXupLvJ6x2T2i6Md1TYvQWeAnuZO70z3V8LzhJlub2hTZT2pZhA1IGKkaZn69L9eGLRQ7lcKMcSAxzk6PZSkixBpyruJOV4endN8DRD20iAqHTsI8LEd25EiY1Th368EFrefIvUarjjm99TkbY4GoNfV+YfwH874F0RFwsgGjZzEmCRt/NJ3BD6R8JUrlx5ZqOe15HyAn0lkmHrAlcxRuzIkLnKqmb05QV4Ova6lL8eSOcpsiEF9GqHtggFOwMnZxj4UfJqsLM15gGCdAERoajgzfGOMMQ7aScMa6VkpZVn104n665yL37r8HBC12a2/+/ezIvhJuhBCe2sjIF+gLb0CVP28bWM/OtKVoyaJ2Q3zY4+EDQoqPsKlVbrCceQ5lzk/NbIvH/cTIYD0F9zpSbJoPJOMXXDtH0wRgaKceQUJwE+wOJ9o7FxQk/YOU7sV3HjykmILNdWLoze8nmsgOSM4TJ4fbvpsECGs4uwhIg5dL9lmQpzVJflGpusvqaG7oHYdCWqF55XO9UOS5onfmCxHydtxO7StjgS2K3WLNtB2WB3tmhHnlMitXlnEZ83Wbe6e4t0ta+6OWgkqpKODUvaQdl89UBXjhh7ZcywYErqvDaA1w9MFfNl8ATnZ1Visoh7wi9fQLPrL1n2ah/J970Vw56GsKSFWVe+rGwIA4S+e2/hy3/8FyijoMUUvDsPuKqluOLsHb+G84iJdxgww3MtVp5Omv2S/QGiHFKQShptr2NtoLISdgY5WEYfauvts9RAWlBUtLA2EQ12nDOtLPUw0Bl6CG+2Hiq2lYUq20rAqqkR+bKu7JQBXeMWmp2kcsST0bYrK/zPg9ideOh9uYoh1jmX8HqU3NT7F71xlPmOpEwCK4h0CaOijR5RV7Ww1P/r4XYfD6eVloHZJzMo8n/xPwlqEdhN9pBDlvJ9UmXeP1F03huGchRmbiAt1ESpNbpDFKGUU4/VeilDHuYSNPnnF2eJFCo+rHuE34sNt2MSzA58TzEozQM7y8qBvtjAAzN5Vgmj4iCapyfum2fvyTjAI7P8VW0uIoqwUtmR96DtmdGCdvREzd5wJ2uvKcgAFDeLUlg8+V6iVH1afZE/JcmRAlRqwAzeGXMzc8Wnz6vG6fVOGAcOjF8KbJy2QkJznhzHvGAN+afl0WbGZDTM2BXCCL20cYqWcimZSSBEF5BI7AD0ERutvza1jMYLeOzFW2pmvgTXWFz+5aIw/oCAgqNwonC7d1ZSK2Yj4hxFyfpKSA4bQAVwtAP7aK4CZmA6eR6mYLcu4PEbwAFnofA04Gb0HUQhI9KA30NlEQenclY/TS91Zc0QMBVV167aOWpHQeyFmCDua171dzA5IS8Y6NAEZGb94xjaF1qDfM1nji5LeVbjiNAVCQI6mScxyHzd1tTSeowODQOyVc9ETviCPPEPdcmX27wYnt2P5XG2mq8nb1ziPIpVAON44B0jZa4kXJXjrkZgRlsze212jOqPGysz/i2z65kjcsxB71mCoFAObqEP5hXR1UuJixpouR5q4DrsWjFU0xE2qL1lCAHJ31pY7QfxRtl5pQOYGpOZISvTPQLlAvQoLasrDS1koPEHO7FYhFeD/f+yJIibhFSvRP7n5cbGZZ5fn6hk4dMIkIG1zC//FRD7ZKmJlYoF8ZbV9zphHjXVd8pj0Os9sEQABqofKdmk6/gWywQOcBWQ2kFk1bxRVbr/7HtRWMi4FgMgDGH4ZiXncTy6CctouJU2ZMbZhtLR0WptjNibj9pmmQ7Ll0APIpbIQWMQbr/S0ZOPpctZPqckZZCtnHSlXer4N1VXml9jlGTIaHJU3qKwAzx9KkEc7mNkkINtZISY2C+4tHCQhzTId1tWdGfyXsbJ+WIt86FxA8dhMZNog4fSiCIpNnoAbDrncgTpCs9WpOMafloTBovmso5YLEo8xckICjO+gWRvumC6Qz57fHLAplU18T4TvVDbGhnNKMmZRu3XPfES9MLl/U+9oYuQsKTQIidkzHd/Ut+fIn+Grn2hZw4CdmeUyK+cpJHQer2ETVorBZOnPahrsnxd7jgIpCyI2rwqFMFbSvx8rN433c0rVBzFA16AoYfPzvoIdJEEP65JScTnF3Hq/7ViPOeWtbMChgGg4H2BwzF9CqKQIAivhw39WN6d8agoWK4cEl0eGE/ZnGJcB74eJZZmAcloB1FUQE2d4MbN2u1QglyB2QZAxYZO+tQxPvt2T1MtkehDjQNH3bq0Bn8fZYhSCYEA31uM2/YWmlHhkE0MWxkxyIZ0OE+MZk420asLIPH1RQc5psGOamWXU2zxB3iRJo/8IwRAYaWTyMwSwUAypqaQAALh9TDC3Xc+300NsWNK4xZc57BPJLuB3caftI12R1l6x6GHT4sIyFTxtzSXot/KrcCn71f+MOQyjpJpVOQaaoao5RzKk3hayqFAA4MnwsgF1t401fvjdx+0gesOxKcaSMgoY4WvzvAYjDR1gdkTArrGiHqo/D1JLp3Hkr2m6gLOFy7UbWUUXlBGLPk9uhQ0ChATOQrUWk8vxnd2T/DyCS7qZgHo4QUCuhr1IVFxYX0n91kTYUPawkcxDrqoFvCgrA8MrZcxh7RwIRP7wqhM4LOdfDx81GMQb8nHZ1QK1m1K3scRmpYQDBZ0beqJC3w4M+xOKXJ7LN6ZEBJG1equzZO+YplM8ggSU8A1NmMcICqJ/MEWy7HZWmhJMyjFG+mxDQt+XoqZplNfjHPvbkkvmw2+eTT9qAM0ZKpWQAi/fAHZt1X5Njb1GZZP98H04Igs2afr7XKxmVBdcdO26xWWiaLQYU08DgYcLd3kfpz6vECZKhTHrOmTiE3SSwEi5GU4UUROlULsu+sGqU00b+XYWt/87VaNOLTcPqfntHgjmvVM3jN+Y53rdVQZxLcEuNrF8j23YBL4I86Pt5Y4zxhPiQGfJiuJ2r0E7ypxoOFKhPFCyyeKLyyi1O4UXmRjdHO+jwTb5BAr93lEBUtETBXs7Vz+7lje1XBfsIUts79EZEFDtpwnRGQ1UdSCCt/oPn1QW6oVZYfKLEJSeF2gknjMsWwYzcheJxc5Qk28xkDOHFEjlUu/VSg8vt2cGD4ZBKMRRC8ZrvlJ1gyk2AJLycf3PUPqXobDum5AN3yvA+CLHO1CiuyAmvxI29HhbjgWhLcKpVCY/zT57aq88XS4HM9zlT840E7cnKZaKJAhvvftURWR0zj1nczC4vXDou9R413Inv9TeDjBovXRuFCtIHrMwnszXxy/nGPOD9IFKQUGy5KHINNT9oTAfvPpeAhYOnuy58R2ft03VIQThb28s+8T/cSRv70j2W5fa1HEzPEC65W91EJEXFwdOejRq1iijHckMqcqjnUQLwWDW9wI92+JGhfdJ+DDspRZWAjfi2qZn4R8oOphyQPHy1qfcoJCCfuPXexqByLUd0bdEjcR6T6JPjhky41wIneSPvnmyD4dOqGmUQp/C0gqROlvlG/LLwkMRrKlr2tSFHjuZN+oQA9kxkN1ktDCj4mwyB4TS4URb8eonyCRfjjDrRwBft//mu5KiePF4yfHOynbdLkWL7sIjnan/hQwpI+PAcI7jihX+owCzwppvY4BFPOKzxUZTNz9DRegH2HRp7JuGcUFeqBGf5R5k4ORzN8rqmyiRbxAvvnAMpNxJIiObJZ8pGGiSjB3BAZu6LAZM7JuQEZdVsuhRnnzHUEhfxIhAQcwOAPvvQIqIHt0gnOv/riRJtern/T4Ma/j+Jb8G7Ma6uoOGuZORaS0S1BxzrjcKPKyh3Cqwcp6ZzrjjTDU9lm4RvkY5J4JW40MYbn5ALFPh0dbaNWVZF60SvgRm2rCe6bKeaLe4unXo0WZZxSPGYPpVqQfTdZj97oiX1/CClPUYIMyJSE/M95/+4O4bdfYSOZVs7UUDsbstkZHP4dB5cfz11wUxMviYDVWQ9PLaRkQLs9lB4AoaBPjTPM7m796DGVmBLWZ0e9sYar1scnT9t3dXPEeNPwsy6D4kKl9mAPz9PD3QlX7BVgVDDfoU2WcFxHdHBG+LTNnrReLs+7VWmuhtG4iwEPBtzQCErLjVG8KEluRsVrJwoY6dVbAJ/gC5BRrXnmiosw+ZKuVC6J7l74hwD84GmOTjTQOMwfegbnCGzcwOl3YPdFzOkA2AHYdh5qASjP+U1Dlsc6AkvzX79EBQfz6ObsGMtKNFoVogopv9/GvYGFBIxunvnTqBUG4OD6gWJJUBPuv2zHlu8XjvyBAI4DtYmhckY5LroD132LuJgLycGrKlWFmUyWsuUhyRcn48XoNMO9WzOJJaZanPVW8aWHwKUF6KNBTNetryV/sgrXKLEyc4bqzNvLmn6QK8e1WSE9vo2I6v6qYC40kktQ63Xx1CmQ8rFoMFbRw73jBpWIj6CJJw5XJYFOXT+eaExMsR+1Qg5Zm9hlhGpeXPRuoQ6XZjzMwimVdO9zhvUmed4PXC8wGbgkY4T7NOyUekcJ+M5F+mosFjVDbhIK/WpAF3J480/HMBiw7lfXst69RNRhN1vO1S/NCjAkNLbkDyhVDSavF801IKxtNGszN+khDf3FdSSfgh/saB4oDD+weouY6vMa4VbIRCSlIkD3QvdKG1nWa+ic70NPGGbgH+2f4tqEyagiu9/xP6PZPuu+Yysz22yMMflWObSqx73WEvih+OxptXGV4G49StNeYo3pm64AtGS74z6hPSNKywQybjcrFq+tzGN6Ookw5EpyGacY5ZMC3CA3TBeltcDeS8T9C53nOio+cWvH9ayDo5PzKibVWKunjtk2OtkQVxU7jcx4HAyPRguqsgK8Ke2ArwSHSrwqgFkZZgxOl8jhvgsQ1p2rgn5ZXAKNOiXZ/xxjxVVijxFF1EE7n0RjgJ8LdOoVWiywFSwQetfxby4zIHcst8tjpcfNRJyBNq9c2daDC0FBx6Dw2ARnAmquH4NWkE12pNSev2t+uRMfNgpUbPQwW8ToxJHUI/tyn7lzXXR+2T7KPL5d7XaQ36+L47mjQ6C5kEDKJ1vdf9Z7puMZLrhnCOVTPmCevohgAgAAAAAA==') center/cover no-repeat;
            opacity: .3;
        }

        .auth-panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(27,67,50,.55) 0%, rgba(27,67,50,.88) 100%);
        }

        .auth-brand {
            position: relative;
            z-index: 2;
        }

        .auth-brand h1 {
            font-family: var(--font-display);
            font-size: 3rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: .5rem;
        }

        .auth-brand h1 span { color: var(--green-200); }

        .auth-brand p {
            color: rgba(255,255,255,.55);
            font-size: .95rem;
            font-weight: 300;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ── Panel derecho ── */
        .auth-panel-right {
            width: 520px;
            flex-shrink: 0;
            background: var(--gray-50);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 3rem;
            overflow-y: auto;
        }

        .auth-heading { margin-bottom: 2rem; }

        .auth-eyebrow {
            font-size: .75rem;
            font-weight: 500;
            color: var(--green-700);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: .4rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-eyebrow::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 2px;
            background: var(--green-700);
        }

        .auth-heading h2 {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1.2;
        }

        .auth-heading h2 em {
            font-style: normal;
            color: var(--green-700);
        }

        /* Tabs de tipo */
        .auth-tabs {
            display: flex;
            gap: .5rem;
            margin-bottom: 1.5rem;
            background: var(--gray-200);
            border-radius: var(--radius-md);
            padding: 4px;
        }

        .auth-tab {
            flex: 1;
            padding: .65rem .5rem;
            border: none;
            border-radius: var(--radius-sm);
            background: transparent;
            color: var(--gray-600);
            font-family: var(--font-body);
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
        }

        .auth-tab.active {
            background: var(--white);
            color: var(--green-800);
            box-shadow: var(--shadow-sm);
        }

        /* Campos */
        .auth-field { margin-bottom: 1rem; }

        .auth-field label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .4rem;
        }

        .auth-field-wrap { position: relative; }

        .auth-field-wrap .auth-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: .95rem;
            pointer-events: none;
            color: var(--gray-400);
        }

        .auth-field input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.6rem;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: .92rem;
            color: var(--gray-900);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .auth-field input:focus {
            border-color: var(--green-700);
            box-shadow: 0 0 0 3px rgba(64,145,108,.12);
        }

        .auth-field input::placeholder { color: var(--gray-400); }

        .auth-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .8rem;
        }

        /* Sección empresa */
        .auth-empresa-section {
            border-top: 1.5px solid var(--gray-200);
            padding-top: 1.2rem;
            margin-top: .5rem;
            display: none;
        }

        .auth-empresa-section.visible { display: block; }

        .auth-section-title {
            font-size: .75rem;
            font-weight: 500;
            color: var(--green-700);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 1rem;
        }

        /* Alertas */
        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 3px solid var(--danger);
            color: var(--danger);
            padding: .75rem 1rem;
            border-radius: var(--radius-md);
            font-size: .85rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .auth-alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid var(--success);
            color: var(--success);
            padding: 1rem;
            border-radius: var(--radius-md);
            font-size: .9rem;
            margin-bottom: 1.2rem;
        }

        /* Botón */
        .auth-submit {
            width: 100%;
            padding: .95rem;
            background: var(--green-800);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            letter-spacing: .03em;
            margin-top: .8rem;
        }

        .auth-submit:hover {
            background: var(--green-700);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(45,106,79,.3);
        }

        .auth-links {
            text-align: center;
            margin-top: 1.2rem;
            font-size: .85rem;
        }

        .auth-links a {
            color: var(--green-700);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .auth-panel-left { display: none; }
            .auth-panel-right { width: 100%; padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Panel izquierdo --}}
<div class="auth-panel-left">
    <div class="auth-brand">
        <span style="font-size:2.5rem;display:block;margin-bottom:.8rem;">
            <i  style="color:var(--green-200);"></i>
        </span>
        <h1>Flow<span>Zone</span></h1>
        <p>Turismo · Ortega, Tolima</p>
    </div>
</div>

{{-- Panel derecho --}}
<div class="auth-panel-right">
    <div class="auth-heading">
        <div class="auth-eyebrow">Únete a nosotros</div>
        <h2>Crea tu <em>cuenta</em><br>gratis</h2>
    </div>

    @if($errors->any())
        <div class="auth-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success') === 'usuario')
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>¡Registro exitoso!</strong> Ya puedes <a href="{{ route('login') }}" style="color:var(--green-800);font-weight:600;">iniciar sesión</a>.
        </div>
    @elseif(session('success') === 'empresa')
        <div class="auth-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <strong>¡Empresa registrada!</strong> Tu cuenta está pendiente de aprobación.
        </div>
    @endif

    <div class="auth-tabs">
        <button type="button" class="auth-tab active" onclick="setTipo(this,'usuario')">
            <i class="fa-solid fa-user fa-xs"></i> Visitante
        </button>
        <button type="button" class="auth-tab" onclick="setTipo(this,'empresa')">
            <i class="fa-solid fa-building fa-xs"></i> Empresa
        </button>
    </div>

    <form method="POST" action="{{ url('/registro') }}">
        @csrf
        <input type="hidden" name="rol" id="campo-rol" value="{{ old('rol', 'usuario') }}">

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Nombre completo *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-user"></i>
                    <input type="text" name="nombre" required maxlength="100"
                           placeholder="Tu nombre" value="{{ old('nombre') }}">
                </div>
            </div>
            <div class="auth-field">
                <label>Teléfono</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-phone"></i>
                    <input type="tel" name="telefono" maxlength="20"
                           placeholder="3201234567" value="{{ old('telefono') }}">
                </div>
            </div>
        </div>

        <div class="auth-field">
            <label>Correo electrónico *</label>
            <div class="auth-field-wrap">
                <i class="auth-icon fa-solid fa-envelope"></i>
                <input type="email" name="correo" required maxlength="150"
                       placeholder="tu@correo.com" value="{{ old('correo') }}">
            </div>
        </div>

        <div class="auth-row-2">
            <div class="auth-field">
                <label>Contraseña *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-lock"></i>
                    <input type="password" name="password" required minlength="6" placeholder="Mín. 6 chars">
                </div>
            </div>
            <div class="auth-field">
                <label>Confirmar *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-lock"></i>
                    <input type="password" name="password_confirmation" required minlength="6" placeholder="Repetir">
                </div>
            </div>
        </div>

        <div class="auth-empresa-section {{ old('rol') === 'empresa' ? 'visible' : '' }}" id="sec-empresa">
            <div class="auth-section-title">
                <i class="fa-solid fa-building fa-xs"></i> Datos de la empresa
            </div>
            <div class="auth-field">
                <label>Nombre de la empresa *</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-building"></i>
                    <input type="text" name="empresa_nombre" maxlength="200"
                           placeholder="Nombre legal" value="{{ old('empresa_nombre') }}">
                </div>
            </div>
            <div class="auth-field">
                <label>Dirección</label>
                <div class="auth-field-wrap">
                    <i class="auth-icon fa-solid fa-location-dot"></i>
                    <input type="text" name="empresa_direccion" maxlength="400"
                           placeholder="Dirección" value="{{ old('empresa_direccion') }}">
                </div>
            </div>
        </div>

        <button type="submit" class="auth-submit">
            <i class="fa-solid fa-user-plus fa-xs"></i> Crear cuenta
        </button>
    </form>

    <div class="auth-links">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
        &nbsp;·&nbsp; <a href="{{ route('home') }}">← Inicio</a>
    </div>
</div>

<script>
function setTipo(btn, tipo) {
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('campo-rol').value = tipo;
    document.getElementById('sec-empresa').classList.toggle('visible', tipo === 'empresa');
}

// Restaurar estado si hay old input
if (document.getElementById('campo-rol').value === 'empresa') {
    document.querySelectorAll('.auth-tab')[1].classList.add('active');
    document.querySelectorAll('.auth-tab')[0].classList.remove('active');
    document.getElementById('sec-empresa').classList.add('visible');
}
</script>
</body>
</html>
