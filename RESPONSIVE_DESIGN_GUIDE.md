# Responsive Design Guide for Calm Mind Massage & Spa

## Quick Reference

### Mobile-First Breakpoints Used

```
xs:   320px   (Mobile phones - extra small)
sm:   640px   (Mobile phones - small)
md:   768px   (Tablets - portrait)
lg:   1024px  (Tablets - landscape, small desktops)
xl:   1280px  (Desktops)
2xl:  1536px  (Large desktops)
```

## Component Scaling Examples

### Text Sizing Pattern
```html
<!-- Small text -->
<p class="text-xs sm:text-sm md:text-base lg:text-lg">Responsive text</p>

<!-- Headings -->
<h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl">Hero Title</h1>
<h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl">Section Title</h2>
```

### Spacing Pattern
```html
<!-- Padding -->
<div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
  Content
</div>

<!-- Gap between items -->
<div class="gap-2 sm:gap-3 md:gap-4 lg:gap-6">
  Items
</div>
```

### Grid Layouts
```html
<!-- Services Grid: 1 col → 2 cols → 3 cols -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
  @foreach($services as $service)
    <div class="card">{{ $service->name }}</div>
  @endforeach
</div>

<!-- Gallery Grid: 2 cols → 3 cols → 4 cols -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
  @foreach($galleries as $gallery)
    <img src="{{ asset($gallery->image_path) }}" />
  @endforeach
</div>
```

### Flex Layouts
```html
<!-- Stack on mobile, row on desktop -->
<div class="flex flex-col sm:flex-row gap-4">
  <button>Button 1</button>
  <button>Button 2</button>
</div>

<!-- Hide on mobile, show on desktop -->
<div class="hidden lg:block">
  Desktop-only content
</div>

<!-- Show on mobile, hide on desktop -->
<div class="md:hidden">
  Mobile menu button
</div>
```

## Navigation Implementation

### Mobile Hamburger Menu
```html
<!-- Button (visible on mobile only) -->
<button id="mobile-menu-btn" class="md:hidden">
  <i class="fas fa-bars"></i>
</button>

<!-- Menu (hidden by default, toggle with JavaScript) -->
<nav id="mobile-menu" class="hidden md:hidden">
  <a href="#">Link 1</a>
  <a href="#">Link 2</a>
</nav>

<!-- Desktop Nav (hidden on mobile) -->
<nav class="hidden md:flex space-x-6">
  <a href="#">Link 1</a>
  <a href="#">Link 2</a>
</nav>
```

## Form Elements

### Responsive Input Fields
```html
<input type="text" 
  class="w-full rounded-lg border border-gray-200 
    px-3 py-2 sm:py-3 text-sm sm:text-base
    focus:outline-none focus:ring-2 focus:ring-blue-200" 
  placeholder="Enter text">
```

### Touch-Friendly Buttons
```html
<button class="px-4 sm:px-6 py-2 sm:py-3 
  min-h-[44px] sm:min-h-[48px]
  text-sm sm:text-base font-semibold
  rounded-lg hover:opacity-95 transition">
  Click Me
</button>
```

## Images & Media

### Responsive Images
```html
<!-- Adapt to container width -->
<img src="image.jpg" alt="Description" class="w-full h-auto">

<!-- Different aspect ratios for different screens -->
<div class="aspect-video md:aspect-square lg:aspect-auto">
  <img src="image.jpg" alt="Description" class="w-full h-full object-cover">
</div>
```

### Container Width
```html
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
  <!-- Auto centering with responsive padding -->
</div>
```

## CSS Media Queries (If Needed)

```css
/* Mobile first - base styles */
.component {
  font-size: 14px;
  padding: 16px;
}

/* Tablet and up */
@media (min-width: 768px) {
  .component {
    font-size: 16px;
    padding: 24px;
  }
}

/* Desktop and up */
@media (min-width: 1024px) {
  .component {
    font-size: 18px;
    padding: 32px;
  }
}
```

## Testing Checklist

- [ ] Test on iPhone SE (375px)
- [ ] Test on iPhone 12/13 (390px)
- [ ] Test on iPad (768px)
- [ ] Test on iPad Pro (1024px)
- [ ] Test on Desktop (1280px+)
- [ ] Test in landscape orientation
- [ ] Test all touch interactions (buttons, forms)
- [ ] Test navigation menu toggle
- [ ] Check for text overflow
- [ ] Verify image scaling
- [ ] Test all interactive elements

## Common Issues & Solutions

### Issue: Text Too Small on Mobile
**Solution**: Add responsive text sizing
```html
<p class="text-sm sm:text-base md:text-lg">Text</p>
```

### Issue: Crowded Layout on Mobile
**Solution**: Use responsive spacing and stacking
```html
<div class="flex flex-col sm:flex-row gap-3 sm:gap-6">
  <div>Item 1</div>
  <div>Item 2</div>
</div>
```

### Issue: Buttons Hard to Click on Mobile
**Solution**: Ensure minimum 44px height
```html
<button class="h-11 sm:h-12 px-4 sm:px-6 min-h-[44px]">
  Click Me
</button>
```

### Issue: Image Distortion
**Solution**: Use object-fit
```html
<img src="image.jpg" class="w-full h-40 sm:h-48 object-cover">
```

## Best Practices

1. **Mobile First**: Start with mobile styles, then enhance for larger screens
2. **Progressive Enhancement**: Ensure basic functionality works on all devices
3. **Touch Targets**: Minimum 44x44px for touch buttons
4. **Readability**: Adequate font sizes and line heights
5. **Performance**: Optimize images for different screen sizes
6. **Testing**: Test on real devices, not just browser dev tools
7. **Accessibility**: Maintain focus states and semantic HTML

## Resources

- [Tailwind CSS Responsive Design](https://tailwindcss.com/docs/responsive-design)
- [MDN Media Queries](https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries)
- [Google Mobile-Friendly Test](https://search.google.com/test/mobile-friendly)
- [Responsive Design Best Practices](https://www.smashingmagazine.com/2011/01/guidelines-for-responsive-web-design/)

---

**For Questions or Issues**: Check the component files in `resources/views/` for implementation examples.
