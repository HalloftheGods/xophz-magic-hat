/**
 * Magic Hat Universal Animated Canvas Library
 *
 * Standalone, zero-dependency HTML5 Canvas 2D engine powering ambient backgrounds
 * across the Xophz Magic Hat theme, Customizer, and Magic Wand page builder.
 * Synchronized with the YouMeOS / Project COMPASS canvas ecosystem.
 *
 * @package Xophz_Magic_Hat
 */

(function(window) {
  'use strict';

  var presets = {};

  // Utility to parse hex/rgb/rgba to rgba string with alpha
  function toRgba(color, alpha) {
    if (!color) return 'rgba(37, 99, 235, ' + alpha + ')';
    if (color.indexOf('rgba') === 0) {
      return color.replace(/[\d\.]+\)$/g, alpha + ')');
    }
    if (color.indexOf('rgb(') === 0) {
      return color.replace('rgb(', 'rgba(').replace(')', ', ' + alpha + ')');
    }
    var hex = color.replace('#', '').trim();
    if (hex.length === 3) {
      hex = hex.split('').map(function(c) { return c + c; }).join('');
    }
    if (hex.length >= 6) {
      var r = parseInt(hex.substring(0, 2), 16);
      var g = parseInt(hex.substring(2, 4), 16);
      var b = parseInt(hex.substring(4, 6), 16);
      return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    }
    return color;
  }

  // ── 1. ELECTRIC WAVES ──────────────────────────────────────────
  presets['electric-wave'] = {
    name: 'Electric Waves',
    init: function(c, ctx, opt) {
      this.offset = 0;
      this.sparks = [];
      for (var i = 0; i < 35; i++) {
        this.sparks.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 1.5,
          vy: (Math.random() - 0.5) * 1.5,
          size: Math.random() * 2 + 1,
          alpha: Math.random() * 0.7 + 0.3
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#2563eb';
      var speed = opt.speed || 1.0;
      this.offset += 0.025 * speed;

      ctx.clearRect(0, 0, w, h);

      // Render 3 harmonic sine layers
      var layers = [
        { freq: 0.003, amp: Math.min(h * 0.18, 80), speed: 1.0, alpha: 0.35, width: 2 },
        { freq: 0.006, amp: Math.min(h * 0.12, 50), speed: 1.4, alpha: 0.5, width: 2.5 },
        { freq: 0.0015, amp: Math.min(h * 0.22, 100), speed: 0.7, alpha: 0.2, width: 1.5 }
      ];

      var midY = h / 2;
      for (var l = 0; l < layers.length; l++) {
        var layer = layers[l];
        ctx.beginPath();
        ctx.strokeStyle = toRgba(color, layer.alpha * (opt.opacity || 0.7));
        ctx.lineWidth = layer.width;

        for (var x = 0; x <= w; x += 6) {
          var y = midY + Math.sin(x * layer.freq + this.offset * layer.speed) * layer.amp;
          if (x === 0) ctx.moveTo(x, y);
          else ctx.lineTo(x, y);
        }
        ctx.stroke();
      }

      // Render energy sparks
      for (var s = 0; s < this.sparks.length; s++) {
        var p = this.sparks[s];
        p.x += p.vx * speed;
        p.y += p.vy * speed;
        if (p.x < 0) p.x = w;
        if (p.x > w) p.x = 0;
        if (p.y < 0) p.y = h;
        if (p.y > h) p.y = 0;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, p.alpha * (opt.opacity || 0.7));
        ctx.fill();
      }
    }
  };

  // ── 2. AURORA SMOKE ───────────────────────────────────────────
  presets['aurora-smoke'] = {
    name: 'Aurora Smoke',
    init: function(c, ctx, opt) {
      this.time = 0;
      this.particles = [];
      for (var i = 0; i < 40; i++) {
        this.particles.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 0.4,
          vy: -Math.random() * 0.6 - 0.2,
          radius: Math.random() * 80 + 40,
          alpha: Math.random() * 0.15 + 0.05
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#62c9ff';
      var speed = opt.speed || 1.0;
      this.time += 0.01 * speed;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.particles.length; i++) {
        var p = this.particles[i];
        p.x += p.vx * speed;
        p.y += p.vy * speed;
        if (p.y < -p.radius) p.y = h + p.radius;
        if (p.x < -p.radius) p.x = w + p.radius;
        if (p.x > w + p.radius) p.x = -p.radius;

        var grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.radius);
        grad.addColorStop(0, toRgba(color, p.alpha * (opt.opacity || 0.7)));
        grad.addColorStop(1, 'rgba(0,0,0,0)');

        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fill();
      }
    }
  };

  // ── 3. CELESTIAL COSMOS ────────────────────────────────────────
  presets['celestial-cosmos'] = {
    name: 'Celestial Cosmos',
    init: function(c, ctx, opt) {
      this.stars = [];
      for (var i = 0; i < 90; i++) {
        this.stars.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          size: Math.random() * 2 + 0.6,
          alpha: Math.random(),
          speed: Math.random() * 0.02 + 0.005
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#2563eb';
      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.stars.length; i++) {
        var s = this.stars[i];
        s.alpha += s.speed * (opt.speed || 1.0);
        var currentAlpha = (Math.sin(s.alpha) + 1) / 2 * 0.8 + 0.2;

        ctx.beginPath();
        ctx.arc(s.x, s.y, s.size, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, currentAlpha * (opt.opacity || 0.7));
        ctx.fill();
      }
    }
  };

  // ── 4. QUANTUM PARTICLES (INTERACTIVE NODES) ───────────────────
  presets['quantum-particles'] = {
    name: 'Quantum Particles',
    init: function(c, ctx, opt) {
      this.nodes = [];
      var count = Math.min(Math.floor(c.width / 22), 55);
      for (var i = 0; i < count; i++) {
        this.nodes.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 0.8,
          vy: (Math.random() - 0.5) * 0.8,
          radius: Math.random() * 2.5 + 1.5
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#2563eb';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.7;
      ctx.clearRect(0, 0, w, h);

      var maxDist = 120;

      for (var i = 0; i < this.nodes.length; i++) {
        var a = this.nodes[i];
        a.x += a.vx * speed;
        a.y += a.vy * speed;
        if (a.x < 0 || a.x > w) a.vx *= -1;
        if (a.y < 0 || a.y > h) a.vy *= -1;

        // Draw connections
        for (var j = i + 1; j < this.nodes.length; j++) {
          var b = this.nodes[j];
          var dx = a.x - b.x;
          var dy = a.y - b.y;
          var dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < maxDist) {
            var lineAlpha = (1 - dist / maxDist) * 0.4 * opacity;
            ctx.beginPath();
            ctx.strokeStyle = toRgba(color, lineAlpha);
            ctx.lineWidth = 1;
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.stroke();
          }
        }

        // Draw node
        ctx.beginPath();
        ctx.arc(a.x, a.y, a.radius, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, 0.7 * opacity);
        ctx.fill();
      }
    }
  };

  // ── 5. CYBER MATRIX ───────────────────────────────────────────
  presets['cyber-matrix'] = {
    name: 'Cyber Matrix',
    init: function(c, ctx, opt) {
      this.fontSize = 14;
      this.columns = Math.floor(c.width / this.fontSize);
      this.drops = [];
      for (var i = 0; i < this.columns; i++) {
        this.drops[i] = Math.floor(Math.random() * -50);
      }
      this.chars = '0123456789ABCDEF@#$%&*'.split('');
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#10b981';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;

      ctx.fillStyle = 'rgba(255, 255, 255, 0.08)';
      ctx.fillRect(0, 0, w, h);

      ctx.fillStyle = toRgba(color, opacity);
      ctx.font = this.fontSize + 'px monospace';

      for (var i = 0; i < this.drops.length; i++) {
        var text = this.chars[Math.floor(Math.random() * this.chars.length)];
        var x = i * this.fontSize;
        var y = this.drops[i] * this.fontSize;

        ctx.fillText(text, x, y);

        if (y > h && Math.random() > 0.975) {
          this.drops[i] = 0;
        }
        this.drops[i] += 0.5 * speed;
      }
    }
  };

  // ── 6. TESSERACT 4D GRID ──────────────────────────────────────
  presets['tesseract-4d'] = {
    name: 'Tesseract 4D Grid',
    init: function(c, ctx, opt) {
      this.angle = 0;
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#2563eb';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.angle += 0.01 * speed;

      ctx.clearRect(0, 0, w, h);
      var cx = w / 2;
      var cy = h / 2;
      var size = Math.min(w, h) * 0.25;

      ctx.strokeStyle = toRgba(color, 0.4 * opacity);
      ctx.lineWidth = 1.5;

      for (var ring = 1; ring <= 4; ring++) {
        var rSize = size * (ring / 4);
        var rot = this.angle * (ring % 2 === 0 ? 1 : -1) * 0.5;

        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(rot);
        ctx.strokeRect(-rSize / 2, -rSize / 2, rSize, rSize);
        ctx.restore();
      }
    }
  };

  // ── 7. BUBBLEGUM SPHERES ───────────────────────────────────────
  presets['bubblegum'] = {
    name: 'Bubblegum Spheres',
    init: function(c, ctx, opt) {
      this.bubbles = [];
      for (var i = 0; i < 22; i++) {
        this.bubbles.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          radius: Math.random() * 24 + 10,
          vy: -Math.random() * 0.8 - 0.3,
          vx: (Math.random() - 0.5) * 0.4,
          wobble: Math.random() * Math.PI
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#ff3366';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.7;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.bubbles.length; i++) {
        var b = this.bubbles[i];
        b.y += b.vy * speed;
        b.x += b.vx * speed;
        b.wobble += 0.03 * speed;
        var r = b.radius + Math.sin(b.wobble) * 2;

        if (b.y < -b.radius * 2) {
          b.y = h + b.radius;
          b.x = Math.random() * w;
        }

        ctx.beginPath();
        ctx.arc(b.x, b.y, r, 0, Math.PI * 2);
        ctx.strokeStyle = toRgba(color, 0.5 * opacity);
        ctx.lineWidth = 2;
        ctx.fillStyle = toRgba(color, 0.1 * opacity);
        ctx.fill();
        ctx.stroke();
      }
    }
  };

  // ── 8. ALPHABET SOUP ──────────────────────────────────────────
  presets['alphabet-soup'] = {
    name: 'Alphabet Soup',
    init: function(c, ctx, opt) {
      this.letters = [];
      var chars = 'COMPASSMAGICWANDYOUMEOS'.split('');
      for (var i = 0; i < 30; i++) {
        this.letters.push({
          char: chars[i % chars.length],
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          size: Math.random() * 16 + 12,
          vx: (Math.random() - 0.5) * 0.3,
          vy: (Math.random() - 0.5) * 0.3,
          rot: Math.random() * Math.PI,
          rotSpeed: (Math.random() - 0.5) * 0.02
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#f59e0b';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.7;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.letters.length; i++) {
        var item = this.letters[i];
        item.x += item.vx * speed;
        item.y += item.vy * speed;
        item.rot += item.rotSpeed * speed;

        if (item.x < 0) item.x = w;
        if (item.x > w) item.x = 0;
        if (item.y < 0) item.y = h;
        if (item.y > h) item.y = 0;

        ctx.save();
        ctx.translate(item.x, item.y);
        ctx.rotate(item.rot);
        ctx.font = 'bold ' + item.size + 'px serif';
        ctx.fillStyle = toRgba(color, 0.45 * opacity);
        ctx.fillText(item.char, 0, 0);
        ctx.restore();
      }
    }
  };

  // ── 9. MIDNIGHT NERD (SYNTHWAVE HORIZON) ─────────────────────────
  presets['midnight-nerd'] = {
    name: 'Midnight Synthwave',
    init: function(c, ctx, opt) {
      this.offset = 0;
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#ec4899';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.offset += 1.2 * speed;

      ctx.clearRect(0, 0, w, h);
      var horizon = h * 0.65;

      // Horizon glow
      var grad = ctx.createLinearGradient(0, horizon - 80, 0, horizon);
      grad.addColorStop(0, 'rgba(0,0,0,0)');
      grad.addColorStop(1, toRgba(color, 0.25 * opacity));
      ctx.fillStyle = grad;
      ctx.fillRect(0, horizon - 80, w, 80);

      // Horizontal grid lines
      ctx.strokeStyle = toRgba(color, 0.35 * opacity);
      ctx.lineWidth = 1;
      for (var y = horizon; y < h; y += (y - horizon + 8) * 0.35) {
        ctx.beginPath();
        ctx.moveTo(0, y);
        ctx.lineTo(w, y);
        ctx.stroke();
      }

      // Perspective lines converging to center horizon
      var cx = w / 2;
      for (var x = -w * 0.5; x <= w * 1.5; x += w * 0.1) {
        ctx.beginPath();
        ctx.moveTo(cx, horizon);
        ctx.lineTo(x + (this.offset % (w * 0.1)), h);
        ctx.stroke();
      }
    }
  };

  // ── 10. WORMHOLE TUNNEL ───────────────────────────────────────
  presets['wormhole'] = {
    name: 'Wormhole Tunnel',
    init: function(c, ctx, opt) {
      this.particles = [];
      for (var i = 0; i < 70; i++) {
        this.particles.push({
          angle: Math.random() * Math.PI * 2,
          dist: Math.random() * 300,
          speed: Math.random() * 2 + 1,
          size: Math.random() * 2 + 0.8
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#62c9ff';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.7;

      ctx.clearRect(0, 0, w, h);
      var cx = w / 2;
      var cy = h / 2;

      for (var i = 0; i < this.particles.length; i++) {
        var p = this.particles[i];
        p.dist += p.speed * 2 * speed;
        if (p.dist > Math.max(w, h)) {
          p.dist = 10;
          p.angle = Math.random() * Math.PI * 2;
        }

        var x = cx + Math.cos(p.angle) * p.dist;
        var y = cy + Math.sin(p.angle) * p.dist;
        var scale = (p.dist / Math.max(w, h)) * p.size * 2;

        ctx.beginPath();
        ctx.arc(x, y, Math.max(scale, 0.5), 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, (p.dist / Math.max(w, h)) * opacity);
        ctx.fill();
      }
    }
  };

  // ── 11. SUN CORONA ───────────────────────────────────────────
  presets['sun-corona'] = {
    name: 'Sun Corona',
    init: function(c, ctx, opt) {
      this.rot = 0;
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#f59e0b';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.rot += 0.008 * speed;

      ctx.clearRect(0, 0, w, h);
      var cx = w / 2;
      var cy = h / 2;
      var radius = Math.min(w, h) * 0.22;

      var grad = ctx.createRadialGradient(cx, cy, radius * 0.5, cx, cy, radius * 1.6);
      grad.addColorStop(0, toRgba(color, 0.4 * opacity));
      grad.addColorStop(1, 'rgba(0,0,0,0)');

      ctx.fillStyle = grad;
      ctx.beginPath();
      ctx.arc(cx, cy, radius * 1.6, 0, Math.PI * 2);
      ctx.fill();

      // Corona ray pulses
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(this.rot);
      for (var i = 0; i < 16; i++) {
        ctx.rotate((Math.PI * 2) / 16);
        ctx.strokeStyle = toRgba(color, 0.2 * opacity);
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(radius, 0);
        ctx.lineTo(radius * 1.4, 0);
        ctx.stroke();
      }
      ctx.restore();
    }
  };

  // ── 12. SATURN RINGS ─────────────────────────────────────────
  presets['saturn-rings'] = {
    name: 'Saturn Rings',
    init: function(c, ctx, opt) {
      this.rot = 0;
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#d97706';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.rot += 0.005 * speed;

      ctx.clearRect(0, 0, w, h);
      var cx = w / 2;
      var cy = h / 2;

      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(-0.35);

      for (var r = 1; r <= 3; r++) {
        var rx = Math.min(w, h) * (0.35 + r * 0.06);
        var ry = rx * 0.32;

        ctx.beginPath();
        ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2);
        ctx.strokeStyle = toRgba(color, (0.4 - r * 0.08) * opacity);
        ctx.lineWidth = 2.5;
        ctx.stroke();
      }
      ctx.restore();
    }
  };

  // ── 13. FLUID MESH ───────────────────────────────────────────
  presets['fluid-mesh'] = {
    name: 'Fluid Ambient Mesh',
    init: function(c, ctx, opt) {
      this.t = 0;
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#2563eb';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.5;
      this.t += 0.01 * speed;

      ctx.clearRect(0, 0, w, h);

      var blobs = [
        { x: w * 0.3 + Math.sin(this.t * 0.8) * w * 0.15, y: h * 0.4 + Math.cos(this.t * 0.6) * h * 0.15, r: Math.min(w, h) * 0.3 },
        { x: w * 0.7 + Math.cos(this.t * 0.7) * w * 0.15, y: h * 0.6 + Math.sin(this.t * 0.9) * h * 0.15, r: Math.min(w, h) * 0.35 }
      ];

      for (var i = 0; i < blobs.length; i++) {
        var b = blobs[i];
        var g = ctx.createRadialGradient(b.x, b.y, 0, b.x, b.y, b.r);
        g.addColorStop(0, toRgba(color, 0.35 * opacity));
        g.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.fillStyle = g;
        ctx.beginPath();
        ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
        ctx.fill();
      }
    }
  };

  // ── 14. WIZARDS TOWER ────────────────────────────────────────
  presets['wizards-tower'] = {
    name: 'Wizards Tower Runes',
    init: function(c, ctx, opt) {
      this.t = 0;
      this.runes = ['α', 'β', 'δ', 'π', 'χ', 'Ω', '⟁', '◇', '☽', '✧', '⊛', '∞'];
      this.floatingRunes = [];
      this.particles = [];
      for (var i = 0; i < 10; i++) {
        this.floatingRunes.push({
          char: this.runes[i % this.runes.length],
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          size: Math.random() * 16 + 14,
          speed: Math.random() * 0.4 + 0.2,
          rot: Math.random() * Math.PI * 2,
          rotSpeed: (Math.random() - 0.5) * 0.02,
          phase: Math.random() * Math.PI * 2
        });
      }
      for (var p = 0; p < 45; p++) {
        this.particles.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 0.4,
          vy: -0.3 - Math.random() * 0.6,
          size: Math.random() * 2.5 + 1,
          alpha: Math.random() * 0.6 + 0.2
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#a855f7';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.t += 0.02 * speed;

      ctx.clearRect(0, 0, w, h);

      // Draw Arcane Particles
      for (var i = 0; i < this.particles.length; i++) {
        var p = this.particles[i];
        p.x += p.vx * speed;
        p.y += p.vy * speed;
        if (p.y < -10) { p.y = h + 10; p.x = Math.random() * w; }
        if (p.x < 0) p.x = w;
        if (p.x > w) p.x = 0;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, p.alpha * opacity);
        ctx.fill();
      }

      // Draw Floating Runes
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      for (var r = 0; r < this.floatingRunes.length; r++) {
        var rune = this.floatingRunes[r];
        rune.y -= rune.speed * speed;
        rune.rot += rune.rotSpeed * speed;
        if (rune.y < -30) { rune.y = h + 30; rune.x = Math.random() * w; }

        var alpha = (0.35 + Math.sin(this.t + rune.phase) * 0.2) * opacity;
        ctx.save();
        ctx.translate(rune.x, rune.y);
        ctx.rotate(rune.rot);
        ctx.font = rune.size + 'px serif';
        ctx.fillStyle = toRgba(color, alpha);
        ctx.fillText(rune.char, 0, 0);
        ctx.restore();
      }
    }
  };

  // ── 15. MAGIC FORMULA ─────────────────────────────────────────
  presets['magic-formula'] = {
    name: 'Magic Formula Flask',
    init: function(c, ctx, opt) {
      this.t = 0;
      this.bubbles = [];
      for (var i = 0; i < 40; i++) {
        this.bubbles.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          radius: Math.random() * 8 + 3,
          speed: Math.random() * 1.2 + 0.6,
          wobbleAmp: Math.random() * 12 + 4,
          wobbleSpeed: Math.random() * 0.04 + 0.02,
          phase: Math.random() * Math.PI * 2,
          alpha: Math.random() * 0.5 + 0.2
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#10b981';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      this.t += 0.03 * speed;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.bubbles.length; i++) {
        var b = this.bubbles[i];
        b.y -= b.speed * speed;
        var wobble = Math.sin(this.t * b.wobbleSpeed * 60 + b.phase) * b.wobbleAmp;
        var bx = b.x + wobble;

        if (b.y < -20) {
          b.y = h + 20;
          b.x = Math.random() * w;
        }

        ctx.beginPath();
        ctx.arc(bx, b.y, b.radius, 0, Math.PI * 2);
        ctx.strokeStyle = toRgba(color, b.alpha * opacity);
        ctx.lineWidth = 1.5;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(bx - b.radius * 0.3, b.y - b.radius * 0.3, b.radius * 0.25, 0, Math.PI * 2);
        ctx.fillStyle = toRgba('#ffffff', b.alpha * opacity * 0.8);
        ctx.fill();
      }
    }
  };

  // ── 16. ENCHIRIDION ───────────────────────────────────────────
  presets['enchiridion'] = {
    name: 'Enchiridion Neural Net',
    init: function(c, ctx, opt) {
      this.nodes = [];
      for (var i = 0; i < 55; i++) {
        this.nodes.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 0.5,
          vy: (Math.random() - 0.5) * 0.5,
          radius: Math.random() * 2.5 + 1.5,
          pulse: Math.random() * Math.PI * 2
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#62c9ff';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      var distLimit = 140;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.nodes.length; i++) {
        var n = this.nodes[i];
        n.x += n.vx * speed;
        n.y += n.vy * speed;
        n.pulse += 0.03 * speed;
        if (n.x < 0) n.x = w;
        if (n.x > w) n.x = 0;
        if (n.y < 0) n.y = h;
        if (n.y > h) n.y = 0;

        for (var j = i + 1; j < this.nodes.length; j++) {
          var n2 = this.nodes[j];
          var dx = n.x - n2.x;
          var dy = n.y - n2.y;
          var d = Math.sqrt(dx * dx + dy * dy);
          if (d < distLimit) {
            var a = (1 - d / distLimit) * 0.35 * opacity;
            ctx.beginPath();
            ctx.moveTo(n.x, n.y);
            ctx.lineTo(n2.x, n2.y);
            ctx.strokeStyle = toRgba(color, a);
            ctx.lineWidth = 1;
            ctx.stroke();
          }
        }

        var r = n.radius + Math.sin(n.pulse) * 0.8;
        ctx.beginPath();
        ctx.arc(n.x, n.y, Math.max(0.5, r), 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, 0.75 * opacity);
        ctx.fill();
      }
    }
  };

  // ── 17. OMEGA SOURCE ──────────────────────────────────────────
  presets['omega-source'] = {
    name: 'Omega Source Vortex',
    init: function(c, ctx, opt) {
      this.t = 0;
      this.particles = [];
      for (var i = 0; i < 90; i++) {
        this.particles.push({
          angle: Math.random() * Math.PI * 2,
          distance: Math.random() * Math.min(c.width, c.height) * 0.45 + 20,
          speed: Math.random() * 0.015 + 0.005,
          size: Math.random() * 2 + 1,
          alpha: Math.random() * 0.6 + 0.2
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#06b6d4';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      var cx = w / 2;
      var cy = h / 2;
      this.t += 0.02 * speed;

      ctx.clearRect(0, 0, w, h);

      // Center Core
      var coreGrad = ctx.createRadialGradient(cx, cy, 0, cx, cy, 60);
      coreGrad.addColorStop(0, toRgba(color, 0.5 * opacity));
      coreGrad.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = coreGrad;
      ctx.beginPath();
      ctx.arc(cx, cy, 60, 0, Math.PI * 2);
      ctx.fill();

      // Swirling Orbitals
      for (var i = 0; i < this.particles.length; i++) {
        var p = this.particles[i];
        p.angle += p.speed * speed;
        p.distance -= 0.15 * speed;
        if (p.distance < 15) {
          p.distance = Math.min(w, h) * 0.45;
        }

        var px = cx + Math.cos(p.angle) * p.distance;
        var py = cy + Math.sin(p.angle) * (p.distance * 0.6);

        ctx.beginPath();
        ctx.arc(px, py, p.size, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, p.alpha * opacity);
        ctx.fill();
      }
    }
  };

  // ── 18. TELESCOPE ─────────────────────────────────────────────
  presets['telescope'] = {
    name: 'Telescope Deep Space',
    init: function(c, ctx, opt) {
      this.stars = [];
      for (var i = 0; i < 110; i++) {
        this.stars.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          radius: Math.random() * 1.5 + 0.5,
          speed: Math.random() * 0.25 + 0.08,
          phase: Math.random() * Math.PI * 2
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#ffffff';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.stars.length; i++) {
        var s = this.stars[i];
        s.x -= s.speed * speed;
        s.phase += 0.02 * speed;
        if (s.x < 0) { s.x = w; s.y = Math.random() * h; }

        var twinkle = (0.4 + Math.sin(s.phase) * 0.4) * opacity;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.radius, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, twinkle);
        ctx.fill();
      }
    }
  };

  // ── 19. LOGOS ─────────────────────────────────────────────────
  presets['logos'] = {
    name: 'Logos Constellation',
    init: function(c, ctx, opt) {
      this.nodes = [];
      for (var i = 0; i < 60; i++) {
        this.nodes.push({
          x: Math.random() * c.width,
          y: Math.random() * c.height,
          vx: (Math.random() - 0.5) * 0.6,
          vy: (Math.random() - 0.5) * 0.6,
          radius: Math.random() * 2 + 1
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#3dee98';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      var distLimit = 120;

      ctx.clearRect(0, 0, w, h);

      for (var i = 0; i < this.nodes.length; i++) {
        var n = this.nodes[i];
        n.x += n.vx * speed;
        n.y += n.vy * speed;
        if (n.x < 0) n.x = w;
        if (n.x > w) n.x = 0;
        if (n.y < 0) n.y = h;
        if (n.y > h) n.y = 0;

        for (var j = i + 1; j < this.nodes.length; j++) {
          var n2 = this.nodes[j];
          var dx = n.x - n2.x;
          var dy = n.y - n2.y;
          var dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < distLimit) {
            var a = (1 - dist / distLimit) * 0.3 * opacity;
            ctx.beginPath();
            ctx.moveTo(n.x, n.y);
            ctx.lineTo(n2.x, n2.y);
            ctx.strokeStyle = toRgba(color, a);
            ctx.stroke();
          }
        }

        ctx.beginPath();
        ctx.arc(n.x, n.y, n.radius, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, 0.7 * opacity);
        ctx.fill();
      }
    }
  };

  // ── 20. NUCLEOS ───────────────────────────────────────────────
  presets['nucleos'] = {
    name: 'Nucleos Atomic Orbits',
    init: function(c, ctx, opt) {
      this.rot = 0;
      this.orbits = [
        { rx: 0.18, ry: 0.08, speed: 0.02, tilt: 0.3 },
        { rx: 0.30, ry: 0.12, speed: -0.015, tilt: -0.6 },
        { rx: 0.42, ry: 0.16, speed: 0.012, tilt: 0.9 },
        { rx: 0.54, ry: 0.20, speed: -0.009, tilt: -1.2 }
      ];
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#38bdf8';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      var cx = w / 2;
      var cy = h / 2;
      var baseR = Math.min(w, h);
      this.rot += 0.01 * speed;

      ctx.clearRect(0, 0, w, h);

      // Core
      ctx.beginPath();
      ctx.arc(cx, cy, 8, 0, Math.PI * 2);
      ctx.fillStyle = toRgba(color, 0.8 * opacity);
      ctx.fill();

      for (var i = 0; i < this.orbits.length; i++) {
        var o = this.orbits[i];
        var rx = baseR * o.rx;
        var ry = baseR * o.ry;

        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(o.tilt);

        ctx.beginPath();
        ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2);
        ctx.strokeStyle = toRgba(color, 0.25 * opacity);
        ctx.lineWidth = 1.2;
        ctx.stroke();

        var electronAngle = this.rot * o.speed * 60;
        var ex = Math.cos(electronAngle) * rx;
        var ey = Math.sin(electronAngle) * ry;

        ctx.beginPath();
        ctx.arc(ex, ey, 4, 0, Math.PI * 2);
        ctx.fillStyle = toRgba('#ffffff', 0.9 * opacity);
        ctx.fill();

        ctx.restore();
      }
    }
  };

  // ── 21. JUPITER GRAVITY ───────────────────────────────────────
  presets['jupiter-gravity'] = {
    name: 'Jupiter Gravitational Lensing',
    init: function(c, ctx, opt) {
      this.particles = [];
      for (var i = 0; i < 80; i++) {
        this.particles.push({
          angle: Math.random() * Math.PI * 2,
          dist: Math.random() * Math.min(c.width, c.height) * 0.45 + 30,
          speed: Math.random() * 0.8 + 0.4,
          size: Math.random() * 2 + 1,
          alpha: Math.random() * 0.5 + 0.3
        });
      }
    },
    render: function(c, ctx, dt, opt) {
      var w = c.width;
      var h = c.height;
      var color = opt.color || '#f59e0b';
      var speed = opt.speed || 1.0;
      var opacity = opt.opacity || 0.6;
      var cx = w / 2;
      var cy = h / 2;

      ctx.clearRect(0, 0, w, h);

      // Central event horizon
      var g = ctx.createRadialGradient(cx, cy, 10, cx, cy, 50);
      g.addColorStop(0, toRgba(color, 0.6 * opacity));
      g.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.fillStyle = g;
      ctx.beginPath();
      ctx.arc(cx, cy, 50, 0, Math.PI * 2);
      ctx.fill();

      for (var i = 0; i < this.particles.length; i++) {
        var p = this.particles[i];
        p.dist -= p.speed * speed;
        p.angle += (0.015 + (1 / Math.max(10, p.dist)) * 0.8) * speed;

        if (p.dist < 20) {
          p.dist = Math.min(w, h) * 0.45;
          p.angle = Math.random() * Math.PI * 2;
        }

        var px = cx + Math.cos(p.angle) * p.dist;
        var py = cy + Math.sin(p.angle) * (p.dist * 0.5);

        ctx.beginPath();
        ctx.arc(px, py, p.size, 0, Math.PI * 2);
        ctx.fillStyle = toRgba(color, p.alpha * opacity);
        ctx.fill();
      }
    }
  };

  // ── MANAGER CONTROLLER ─────────────────────────────────────────

  function MagicHatCanvasManager(canvas, presetName, options) {
    this.canvas = canvas;
    this.ctx = canvas.getContext('2d');
    this.presetName = presetName || 'electric-wave';
    this.options = options || {};
    this.animId = null;
    this.isRunning = false;

    this.resize = this.resize.bind(this);
    this.loop = this.loop.bind(this);

    window.addEventListener('resize', this.resize, { passive: true });
    this.resize();
    this.start();
  }

  MagicHatCanvasManager.prototype.resize = function() {
    if (!this.canvas) return;
    var dpr = window.devicePixelRatio || 1;
    var rect = this.canvas.getBoundingClientRect();
    var width = rect.width || window.innerWidth;
    var height = rect.height || window.innerHeight;

    this.canvas.width = width * dpr;
    this.canvas.height = height * dpr;
    this.ctx.scale(dpr, dpr);

    var preset = presets[this.presetName];
    if (preset && typeof preset.init === 'function') {
      preset.init({ width: width, height: height }, this.ctx, this.options);
    }
  };

  MagicHatCanvasManager.prototype.start = function() {
    if (this.isRunning) return;
    this.isRunning = true;
    this.loop();
  };

  MagicHatCanvasManager.prototype.stop = function() {
    this.isRunning = false;
    if (this.animId) {
      cancelAnimationFrame(this.animId);
      this.animId = null;
    }
  };

  MagicHatCanvasManager.prototype.loop = function(timestamp) {
    if (!this.isRunning) return;
    var preset = presets[this.presetName];
    if (preset && typeof preset.render === 'function') {
      var dpr = window.devicePixelRatio || 1;
      var w = this.canvas.width / dpr;
      var h = this.canvas.height / dpr;
      preset.render({ width: w, height: h }, this.ctx, timestamp || 0, this.options);
    }
    this.animId = requestAnimationFrame(this.loop);
  };

  MagicHatCanvasManager.prototype.updateOptions = function(newOpts) {
    for (var k in newOpts) {
      if (newOpts.hasOwnProperty(k)) {
        this.options[k] = newOpts[k];
      }
    }
  };

  MagicHatCanvasManager.prototype.switchPreset = function(newPreset) {
    if (!presets[newPreset]) return;
    this.presetName = newPreset;
    this.resize();
  };

  MagicHatCanvasManager.prototype.destroy = function() {
    this.stop();
    window.removeEventListener('resize', this.resize);
  };

  // Expose global registry
  window.MagicHatCanvases = {
    presets: presets,
    Manager: MagicHatCanvasManager,
    mount: function(el, preset, opts) {
      return new MagicHatCanvasManager(el, preset, opts);
    }
  };

})(window);
