import React, { useState, useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { navigateTo } from '../navigation';
import { LucideIcon } from './LucideIcon';
import { OptimizedImage } from './OptimizedImage';

interface Author {
  id: number;
  name: string;
  profile_photo: string | null;
  designation: string | null;
  biography: string | null;
  email: string | null;
  website: string | null;
  facebook_url: string | null;
  linkedin_url: string | null;
  twitter_url: string | null;
}

interface Category {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  featured_image: string | null;
}

interface Tag {
  id: number;
  name: string;
  slug: string;
}

interface BlogPost {
  id: string;
  slug: string;
  title: string;
  excerpt: string;
  coverImage: string;
  contentHtml: string;
  seoTitle: string | null;
  seoDescription: string | null;
  status: string;
  createdAt: string;
  updatedAt: string;
  is_featured: boolean;
  is_pinned: boolean;
  reading_time: number;
  author?: Author | null;
  category?: Category | null;
  tags?: Tag[];
  focus_keyword?: string | null;
  canonical_url?: string | null;
  schema_type?: string;
  breadcrumb_title?: string | null;
}

interface BlogPageProps {
  posts: BlogPost[]; // Fallback list
  slug?: string;
}

export const BlogPage: React.FC<BlogPageProps> = ({ posts: fallbackPosts, slug }) => {
  // Main API data states
  const [posts, setPosts] = useState<BlogPost[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [tags, setTags] = useState<Tag[]>([]);
  const [loading, setLoading] = useState(true);

  // Listing page interactive states
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategorySlug, setSelectedCategorySlug] = useState<string>('all');
  const [selectedTagSlug, setSelectedTagSlug] = useState<string>('all');
  const [visibleCount, setVisibleCount] = useState(6);

  // Details page states
  const [detailPost, setDetailPost] = useState<BlogPost | null>(null);
  const [relatedPosts, setRelatedPosts] = useState<BlogPost[]>([]);
  const [prevPost, setPrevPost] = useState<{ slug: string; title: string } | null>(null);
  const [nextPost, setNextPost] = useState<{ slug: string; title: string } | null>(null);
  const [headings, setHeadings] = useState<Array<{ id: string; text: string; level: number }>>([]);
  const [copied, setCopied] = useState(false);

  // Fetch blogs list
  useEffect(() => {
    const fetchBlogsData = async () => {
      try {
        const res = await fetch('/api/blogs');
        if (res.ok) {
          const json = await res.json();
          setPosts(json.posts || []);
          setCategories(json.categories || []);
          setTags(json.tags || []);
        } else {
          setPosts(fallbackPosts);
        }
      } catch (err) {
        console.error('Failed to fetch blogs from API', err);
        setPosts(fallbackPosts);
      } finally {
        setLoading(false);
      }
    };
    fetchBlogsData();
  }, [fallbackPosts]);

  // Fetch individual blog detail
  useEffect(() => {
    if (!slug) {
      setDetailPost(null);
      return;
    }
    const fetchDetailData = async () => {
      setLoading(true);
      try {
        const res = await fetch(`/api/blogs/${slug}`);
        if (res.status === 301 || res.status === 308) {
          const redirect = await res.json();
          if (redirect.redirectSlug) {
            navigateTo(`/blog/${redirect.redirectSlug}`);
            return;
          }
        }
        if (res.ok) {
          const json = await res.json();
          setDetailPost(json.post);
          setRelatedPosts(json.relatedPosts || []);
          setPrevPost(json.prevPost);
          setNextPost(json.nextPost);

          // Parse headings for Table of Contents from HTML
          if (json.post?.contentHtml) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = json.post.contentHtml;
            const headingEls = tempDiv.querySelectorAll('h2, h3, h4');
            const items: Array<{ id: string; text: string; level: number }> = [];
            headingEls.forEach((el, index) => {
              const text = el.textContent || '';
              // Create clean ID if not present
              const id = el.id || `heading-${index}`;
              el.id = id;
              const level = parseInt(el.tagName.replace('H', ''), 10);
              items.push({ id, text, level });
            });
            setHeadings(items);

            // Re-inject modified IDs back into the post HTML
            json.post.contentHtml = tempDiv.innerHTML;
          }
        }
      } catch (err) {
        console.error('Failed to load blog detail', err);
      } finally {
        setLoading(false);
      }
    };
    fetchDetailData();
  }, [slug]);

  // Handle SEO Structured Data injection (JSON-LD)
  useEffect(() => {
    if (!detailPost) return;

    // Build JSON-LD object
    const schema = {
      '@context': 'https://schema.org',
      '@type': detailPost.schema_type || 'BlogPosting',
      'headline': detailPost.title,
      'alternativeHeadline': detailPost.excerpt,
      'image': detailPost.coverImage ? window.location.origin + detailPost.coverImage : '',
      'genre': detailPost.category?.name || 'Grooming',
      'keywords': detailPost.focus_keyword || '',
      'publisher': {
        '@type': 'Organization',
        'name': 'Adonis Men\'s Grooming Salon',
        'logo': {
          '@type': 'ImageObject',
          'url': window.location.origin + '/assets/images/logo.png'
        }
      },
      'url': window.location.href,
      'datePublished': detailPost.published_at || detailPost.createdAt,
      'dateCreated': detailPost.createdAt,
      'dateModified': detailPost.updatedAt,
      'description': detailPost.seoDescription || detailPost.excerpt,
      'author': {
        '@type': 'Person',
        'name': detailPost.author?.name || 'Adonis Editor',
        'jobTitle': detailPost.author?.designation || 'Master Stylist'
      }
    };

    const scriptId = 'blog-jsonld-schema';
    let scriptEl = document.getElementById(scriptId);
    if (!scriptEl) {
      scriptEl = document.createElement('script');
      scriptEl.setAttribute('id', scriptId);
      scriptEl.setAttribute('type', 'application/ld+json');
      document.head.appendChild(scriptEl);
    }
    scriptEl.innerHTML = JSON.stringify(schema);

    return () => {
      document.getElementById(scriptId)?.remove();
    };
  }, [detailPost]);

  // Loading state
  if (loading) {
    return (
      <div className="w-full bg-salon-black min-h-screen flex items-center justify-center pt-28">
        <div className="text-center space-y-4">
          <div className="inline-block animate-spin rounded-full h-8 w-8 border-2 border-t-gold-400 border-white/10"></div>
          <p className="text-xs text-gray-500 font-mono uppercase tracking-widest">Loading Adonis Journal...</p>
        </div>
      </div>
    );
  }

  // Social Sharing Actions
  const handleShare = (platform: string) => {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(detailPost?.title || '');
    let shareUrl = '';
    if (platform === 'facebook') shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
    else if (platform === 'twitter') shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
    else if (platform === 'linkedin') shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${url}&title=${title}`;
    else if (platform === 'copy') {
      navigator.clipboard.writeText(window.location.href);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
      return;
    }
    window.open(shareUrl, '_blank', 'width=600,height=400');
  };

  // Scroll to TOC element smoothly
  const scrollToHeading = (id: string) => {
    const el = document.getElementById(id);
    if (el) {
      const offset = 100; // Sticky header offset
      const bodyRect = document.body.getBoundingClientRect().top;
      const elementRect = el.getBoundingClientRect().top;
      const elementPosition = elementRect - bodyRect;
      const offsetPosition = elementPosition - offset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  };

  // 1. DETAIL VIEW RENDER
  if (slug && detailPost) {
    const robotsContent = `${detailPost.robots_index ? 'index' : 'noindex'}, ${detailPost.robots_follow ? 'follow' : 'nofollow'}`;

    return (
      <main className="bg-salon-black text-white min-h-screen pt-28 pb-20 font-sans leading-relaxed selection:bg-gold-400 selection:text-salon-black">
        {/* Robots tags injection helper */}
        <head>
          <title>{detailPost.seoTitle || detailPost.title}</title>
          <meta name="description" content={detailPost.seoDescription || detailPost.excerpt} />
          <meta name="robots" content={robotsContent} />
          {detailPost.canonical_url && <link rel="canonical" href={detailPost.canonical_url} />}
        </head>

        <article className="max-w-7xl mx-auto px-4 md:px-8 space-y-10">
          {/* Breadcrumb Navigation */}
          <nav className="flex items-center gap-2 text-[10px] font-mono uppercase tracking-widest text-gray-500 text-left">
            <a href="/" className="hover:text-white transition-colors">Lounge</a>
            <span>/</span>
            <a href="/blog" className="hover:text-white transition-colors">Blog</a>
            <span>/</span>
            {detailPost.category && (
              <>
                <span className="text-gold-400">{detailPost.category.name}</span>
                <span>/</span>
              </>
            )}
            <span className="text-gray-400 truncate max-w-[200px]">{detailPost.breadcrumb_title || 'Article'}</span>
          </nav>

          {/* Header Title Grid */}
          <div className="space-y-4 text-left max-w-4xl">
            <span className="px-2 py-0.5 border border-gold-400/20 bg-gold-400/5 text-[9px] font-mono uppercase tracking-widest text-gold-400">
              {detailPost.category?.name || 'Grooming Journal'}
            </span>
            <h1 className="font-serif text-3xl sm:text-4xl md:text-5xl uppercase tracking-wider leading-tight text-white">
              {detailPost.title}
            </h1>
            <div className="flex flex-wrap gap-4 items-center text-[10px] font-mono text-gray-400 uppercase tracking-wider">
              <span className="flex items-center gap-1">
                <LucideIcon name="Calendar" size={12} />
                {new Date(detailPost.published_at || detailPost.createdAt).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
              </span>
              <span>•</span>
              <span className="flex items-center gap-1">
                <LucideIcon name="Clock" size={12} />
                {detailPost.reading_time || 5} Min Read
              </span>
              {detailPost.author && (
                <>
                  <span>•</span>
                  <span className="text-gold-400">By {detailPost.author.name}</span>
                </>
              )}
            </div>
          </div>

          {/* Large cover image */}
          {detailPost.coverImage && (
            <div className="blog-header-image-container aspect-[21/9] w-full max-h-[500px] overflow-hidden border border-white/5 bg-[#050505] relative shrink-0">
              <OptimizedImage
                src={detailPost.coverImage}
                alt={detailPost.title}
                className="w-full h-full object-contain object-center brightness-95 block"
                width={1600}
                height={700}
                sizes="100vw"
                loading="eager"
              />
            </div>
          )}

          {/* Main content grid */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
            {/* Left/Main Column */}
            <div className="lg:col-span-8 space-y-8 text-left">
              {/* Dynamic Table of Contents (Only render if headings exist) */}
              {headings.length > 0 && (
                <div className="bg-salon-gray/20 border border-white/5 p-6 rounded-none">
                  <h5 className="font-serif text-xs uppercase tracking-widest text-gold-400 font-bold mb-4 flex items-center gap-2">
                    <LucideIcon name="List" size={14} /> Table of Contents
                  </h5>
                  <ul className="space-y-2.5 text-xs text-gray-400">
                    {headings.map(h => (
                      <li 
                        key={h.id} 
                        style={{ paddingLeft: `${(h.level - 2) * 12}px` }}
                        className="hover:text-gold-400 transition-colors"
                      >
                        <button 
                          onClick={() => scrollToHeading(h.id)}
                          className="text-left hover:underline cursor-pointer"
                        >
                          {h.level === 3 ? '↳ ' : ''}{h.text}
                        </button>
                      </li>
                    ))}
                  </ul>
                </div>
              )}

              {/* Rich-formatted body HTML */}
              <div 
                className="blog-content text-gray-300 text-xs sm:text-sm leading-relaxed space-y-6 markup-content"
                dangerouslySetInnerHTML={{ __html: detailPost.contentHtml }}
              />

              {/* Tags Cloud */}
              {detailPost.tags && detailPost.tags.length > 0 && (
                <div className="flex flex-wrap gap-2 pt-6 border-t border-white/5">
                  {detailPost.tags.map(t => (
                    <button
                      key={t.id}
                      onClick={() => navigateTo(`/blog?tag=${t.slug}`)}
                      className="px-2.5 py-1 bg-salon-gray text-gray-400 hover:text-white hover:bg-gold-400/20 text-[9px] font-mono uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      #{t.name}
                    </button>
                  ))}
                </div>
              )}

              {/* Author Box */}
              {detailPost.author && (
                <div className="bg-salon-gray/20 border border-white/5 p-6 md:p-8 flex flex-col md:flex-row gap-6 items-start">
                  {detailPost.author.profile_photo && (
                    <div className="h-16 w-16 rounded-none overflow-hidden bg-salon-gray border border-white/10 shrink-0">
                      <img 
                        src={detailPost.author.profile_photo} 
                        alt={detailPost.author.name} 
                        className="h-full w-full object-cover" 
                      />
                    </div>
                  )}
                  <div className="space-y-3">
                    <div>
                      <h4 className="font-serif text-sm uppercase tracking-wider text-white font-bold">{detailPost.author.name}</h4>
                      <p className="text-[9px] font-mono text-gold-400 uppercase tracking-widest mt-0.5">{detailPost.author.designation || 'Contributing Stylist'}</p>
                    </div>
                    <p className="text-[11px] text-gray-400 leading-relaxed font-light">{detailPost.author.biography}</p>
                    <div className="flex gap-3 items-center">
                      {detailPost.author.facebook_url && (
                        <a href={detailPost.author.facebook_url} target="_blank" rel="noreferrer" className="text-gray-500 hover:text-white transition-colors">
                          <LucideIcon name="Facebook" size={13} />
                        </a>
                      )}
                      {detailPost.author.linkedin_url && (
                        <a href={detailPost.author.linkedin_url} target="_blank" rel="noreferrer" className="text-gray-500 hover:text-white transition-colors">
                          <LucideIcon name="Linkedin" size={13} />
                        </a>
                      )}
                      {detailPost.author.twitter_url && (
                        <a href={detailPost.author.twitter_url} target="_blank" rel="noreferrer" className="text-gray-500 hover:text-white transition-colors">
                          <LucideIcon name="Twitter" size={13} />
                        </a>
                      )}
                    </div>
                  </div>
                </div>
              )}

              {/* Previous / Next Navigation */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-white/5">
                {prevPost ? (
                  <button 
                    onClick={() => navigateTo(`/blog/${prevPost.slug}`)}
                    className="p-4 bg-salon-gray/10 hover:bg-salon-gray/20 border border-white/5 hover:border-gold-400/20 text-left transition-colors cursor-pointer group flex flex-col justify-between"
                  >
                    <span className="text-[8px] font-mono uppercase text-gray-500 tracking-widest">← Previous Post</span>
                    <span className="text-xs font-serif uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors mt-2 line-clamp-1">{prevPost.title}</span>
                  </button>
                ) : <div />}

                {nextPost ? (
                  <button 
                    onClick={() => navigateTo(`/blog/${nextPost.slug}`)}
                    className="p-4 bg-salon-gray/10 hover:bg-salon-gray/20 border border-white/5 hover:border-gold-400/20 text-right transition-colors cursor-pointer group flex flex-col justify-between items-end"
                  >
                    <span className="text-[8px] font-mono uppercase text-gray-500 tracking-widest">Next Post →</span>
                    <span className="text-xs font-serif uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors mt-2 line-clamp-1">{nextPost.title}</span>
                  </button>
                ) : <div />}
              </div>
            </div>

            {/* Right Sidebar Column */}
            <div className="lg:col-span-4 space-y-8 text-left">
              {/* Share block */}
              <div className="bg-salon-gray/20 border border-white/5 p-6 space-y-4">
                <h5 className="font-serif text-xs uppercase tracking-widest text-white font-bold border-b border-white/5 pb-2">Share Article</h5>
                <div className="flex items-center gap-3">
                  <button onClick={() => handleShare('facebook')} className="p-2 bg-salon-gray text-gray-400 hover:text-white hover:bg-gold-400/20 transition-all cursor-pointer">
                    <LucideIcon name="Facebook" size={14} />
                  </button>
                  <button onClick={() => handleShare('twitter')} className="p-2 bg-salon-gray text-gray-400 hover:text-white hover:bg-gold-400/20 transition-all cursor-pointer">
                    <LucideIcon name="Twitter" size={14} />
                  </button>
                  <button onClick={() => handleShare('linkedin')} className="p-2 bg-salon-gray text-gray-400 hover:text-white hover:bg-gold-400/20 transition-all cursor-pointer">
                    <LucideIcon name="Linkedin" size={14} />
                  </button>
                  <button onClick={() => handleShare('copy')} className="p-2 bg-salon-gray text-gray-400 hover:text-white hover:bg-gold-400/20 transition-all cursor-pointer flex items-center gap-1.5 text-[9px] font-mono uppercase tracking-wider">
                    <LucideIcon name="Link" size={12} />
                    {copied ? 'Copied' : 'Copy link'}
                  </button>
                </div>
              </div>

              {/* Related Posts */}
              {relatedPosts.length > 0 && (
                <div className="space-y-5 bg-salon-gray/20 border border-white/5 p-6">
                  <h5 className="font-serif text-xs uppercase tracking-widest text-white font-bold border-b border-white/5 pb-2">Related Articles</h5>
                  <div className="space-y-4">
                    {relatedPosts.map(rp => (
                      <button 
                        key={rp.id}
                        onClick={() => navigateTo(`/blog/${rp.slug}`)}
                        className="w-full flex gap-3 text-left items-start group cursor-pointer"
                      >
                        <div className="h-12 w-16 overflow-hidden bg-salon-gray border border-white/5 shrink-0">
                          <img src={rp.coverImage} alt={rp.title} className="h-full w-full object-cover brightness-90 group-hover:scale-105 transition-transform" />
                        </div>
                        <div className="min-w-0">
                          <h6 className="font-serif text-[11px] uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors line-clamp-2 leading-tight">{rp.title}</h6>
                          <span className="text-[8px] font-mono text-gray-500 uppercase tracking-widest mt-1 block">
                            {new Date(rp.published_at || rp.createdAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                          </span>
                        </div>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Booking CTA Banner */}
              <div className="bg-gradient-to-br from-[#111] to-salon-gray border border-[#32BBED]/15 p-6 text-center space-y-4">
                <span className="text-[9px] font-mono uppercase text-[#32BBED] tracking-widest font-bold block">Adonis Signature bay</span>
                <h4 className="font-serif text-sm uppercase tracking-wider text-white">Experience luxury Grooming</h4>
                <p className="text-[10px] text-gray-400 leading-relaxed font-light">Book your private styling suite session at our Gulshan or Bashundhara lounge terminals.</p>
                <button
                  onClick={() => navigateTo('/book')}
                  className="w-full py-2 bg-[#32BBED] hover:bg-gold-400 text-black text-[10px] font-serif uppercase font-bold tracking-widest transition-all cursor-pointer text-center block"
                >
                  Book Appointment Now
                </button>
              </div>
            </div>
          </div>
        </article>
      </main>
    );
  }

  // 2. LISTING PAGE RENDER
  // Filter published posts
  const publishedPosts = posts
    .filter(p => p.status === 'published')
    .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());

  // Filter based on selected categories/tags/search
  const filteredPosts = publishedPosts.filter(post => {
    const matchesSearch = !searchQuery.trim() || 
      post.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
      (post.excerpt || '').toLowerCase().includes(searchQuery.toLowerCase()) ||
      post.contentHtml.toLowerCase().includes(searchQuery.toLowerCase());

    const matchesCategory = selectedCategorySlug === 'all' || 
      (post.category && post.category.slug === selectedCategorySlug);

    const matchesTag = selectedTagSlug === 'all' || 
      (post.tags && post.tags.some(t => t.slug === selectedTagSlug));

    return matchesSearch && matchesCategory && matchesTag;
  });

  const featuredPost = filteredPosts.find(p => p.is_featured || p.is_pinned) || filteredPosts[0];
  const regularPosts = filteredPosts.filter(p => p.id !== (featuredPost?.id || ''));

  return (
    <main className="bg-salon-black text-white min-h-screen pt-28 pb-20 font-sans leading-relaxed selection:bg-gold-400 selection:text-salon-black">
      <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-12">
        {/* Banner Section */}
        <motion.div
          initial={{ opacity: 0, y: 22 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.75 }}
          className="text-center max-w-3xl mx-auto space-y-4"
        >
          <span className="text-[10px] font-mono text-gold-400 uppercase tracking-[0.3em] gold-glow">Adonis Editorial Journal</span>
          <h1 className="font-serif text-3xl md:text-5xl uppercase tracking-wider text-white">Grooming & Style Guide</h1>
          <p className="text-xs sm:text-sm text-gray-400 leading-relaxed max-w-xl mx-auto">
            Expert styling tips, straight-razor shaving guides, skin-care methodologies, and luxury men's wellness instructions.
          </p>
        </motion.div>

        {/* Dynamic Filters Deck (Search + Categories + Tags) */}
        <div className="bg-salon-gray/25 border border-white/5 p-5 md:p-6 space-y-4 text-left">
          <div className="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            {/* Search */}
            <div className="md:col-span-4 relative">
              <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600">
                <LucideIcon name="Search" size={13} />
              </span>
              <input
                type="text"
                value={searchQuery}
                onChange={e => { setSearchQuery(e.target.value); setVisibleCount(6); }}
                placeholder="Search articles..."
                className="w-full bg-salon-black text-white text-xs border border-white/10 pl-8 pr-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors rounded-none"
              />
            </div>

            {/* Categories filters */}
            <div className="md:col-span-8 flex flex-wrap gap-2 items-center justify-start md:justify-end">
              <button
                onClick={() => { setSelectedCategorySlug('all'); setVisibleCount(6); }}
                className={`px-3 py-1.5 text-[9px] font-mono uppercase tracking-wider border transition-all ${
                  selectedCategorySlug === 'all'
                    ? 'bg-gold-400 border-gold-400 text-black font-bold'
                    : 'bg-salon-black border-white/5 text-gray-400 hover:text-white hover:border-white/15'
                }`}
              >
                All Categories
              </button>
              {categories.map(cat => (
                <button
                  key={cat.id}
                  onClick={() => { setSelectedCategorySlug(cat.slug); setVisibleCount(6); }}
                  className={`px-3 py-1.5 text-[9px] font-mono uppercase tracking-wider border transition-all ${
                    selectedCategorySlug === cat.slug
                      ? 'bg-gold-400 border-gold-400 text-black font-bold'
                      : 'bg-salon-black border-white/5 text-gray-400 hover:text-white hover:border-white/15'
                  }`}
                >
                  {cat.name}
                </button>
              ))}
            </div>
          </div>

          {/* Tags Cloud Filter */}
          {tags.length > 0 && (
            <div className="pt-3 border-t border-white/5 flex flex-wrap gap-2 items-center">
              <span className="text-[8px] font-mono uppercase tracking-widest text-gray-500">Filter Tags:</span>
              <button
                onClick={() => { setSelectedTagSlug('all'); setVisibleCount(6); }}
                className={`px-2 py-0.5 text-[8px] font-mono uppercase tracking-wider transition-all ${
                  selectedTagSlug === 'all' ? 'text-gold-400 underline font-bold' : 'text-gray-400 hover:text-white'
                }`}
              >
                #Any
              </button>
              {tags.map(tag => (
                <button
                  key={tag.id}
                  onClick={() => { setSelectedTagSlug(tag.slug); setVisibleCount(6); }}
                  className={`px-2 py-0.5 text-[8px] font-mono uppercase tracking-wider transition-all ${
                    selectedTagSlug === tag.slug ? 'text-[#32BBED] underline font-bold' : 'text-gray-400 hover:text-white'
                  }`}
                >
                  #{tag.name}
                </button>
              ))}
            </div>
          )}
        </div>

        {/* 2.1 FEATURED BLOG SECTION */}
        {featuredPost && selectedCategorySlug === 'all' && selectedTagSlug === 'all' && !searchQuery && (
          <motion.div
            initial={{ opacity: 0, scale: 0.98 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8 }}
            className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-salon-gray/10 border border-white/5 p-6 md:p-8 hover:border-gold-400/20 transition-all text-left"
          >
            {/* Featured Image */}
            <div className="lg:col-span-7 blog-featured-image-container aspect-[16/9] w-full max-h-[460px] overflow-hidden bg-[#050505] relative group shrink-0">
              <OptimizedImage
                src={featuredPost.coverImage}
                alt={featuredPost.title}
                className="w-full h-full object-contain object-center brightness-90 group-hover:scale-[1.01] transition-transform duration-700 block"
                width={1200}
                height={700}
                sizes="(max-width: 640px) 100vw, 700px"
              />
              <span className="absolute top-4 left-4 bg-gold-400 text-black px-2 py-0.5 text-[8px] font-mono uppercase tracking-widest font-extrabold shadow-lg z-10">
                Featured Article
              </span>
            </div>

            {/* Featured Content */}
            <div className="lg:col-span-5 space-y-4">
              <div className="flex items-center gap-3 text-[9px] font-mono text-gray-500 uppercase tracking-widest">
                <span>{featuredPost.category?.name || 'Grooming Guide'}</span>
                <span>•</span>
                <span>{featuredPost.reading_time || 5} Min Read</span>
              </div>
              <h2 className="font-serif text-xl sm:text-2xl uppercase tracking-wider text-white hover:text-gold-400 transition-colors cursor-pointer line-clamp-3" onClick={() => navigateTo(`/blog/${featuredPost.slug}`)}>
                {featuredPost.title}
              </h2>
              <p className="text-[11px] sm:text-xs text-gray-400 leading-relaxed line-clamp-3 font-light">
                {featuredPost.excerpt}
              </p>
              
              {/* Author Badge */}
              {featuredPost.author && (
                <div className="flex items-center gap-2 pt-2 border-t border-white/5">
                  {featuredPost.author.profile_photo && (
                    <img src={featuredPost.author.profile_photo} alt={featuredPost.author.name} className="h-6 w-6 rounded-none object-cover border border-white/10" />
                  )}
                  <div className="text-[9px] font-mono text-gray-400 uppercase tracking-wider">
                    By <span className="text-white font-bold">{featuredPost.author.name}</span>
                  </div>
                </div>
              )}

              <button
                onClick={() => navigateTo(`/blog/${featuredPost.slug}`)}
                className="inline-flex items-center gap-2 text-[10px] font-mono uppercase tracking-widest text-gold-400 hover:text-white transition-colors cursor-pointer pt-2"
              >
                Read Article
                <LucideIcon name="ArrowRight" size={13} />
              </button>
            </div>
          </motion.div>
        )}

        {/* 2.2 ARTICLE LISTING GRID */}
        {filteredPosts.length === 0 ? (
          <div className="py-16 text-center space-y-2">
            <LucideIcon name="SearchX" size={32} className="text-gray-600 mx-auto" />
            <p className="text-xs text-gray-500 font-mono uppercase tracking-widest">No articles found matching filters</p>
          </div>
        ) : (
          <div className="space-y-12">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
              {regularPosts.slice(0, visibleCount).map((post, index) => (
                <motion.article
                  key={post.id}
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.6, delay: index * 0.05 }}
                  className="bg-salon-gray/20 border border-white/5 hover:border-gold-400/25 transition-all group flex flex-col justify-between"
                >
                  <button onClick={() => navigateTo(`/blog/${post.slug}`)} className="block w-full text-left flex flex-col h-full justify-between">
                    <div>
                      {/* Featured image */}
                      <div className="blog-card-image-container aspect-[16/10] w-full max-h-[250px] overflow-hidden bg-[#050505] relative shrink-0">
                        <OptimizedImage
                          src={post.coverImage}
                          alt={post.title}
                          className="h-full w-full object-contain object-center brightness-90 group-hover:scale-[1.02] transition-transform duration-500 block"
                          width={600}
                          height={375}
                          sizes="(max-width: 640px) 100vw, 350px"
                        />
                        {post.category && (
                          <span className="absolute bottom-3 left-3 bg-salon-black/90 px-2 py-0.5 border border-gold-400/20 text-[8px] font-mono text-gold-400 tracking-wider z-10">
                            {post.category.name}
                          </span>
                        )}
                      </div>

                      {/* Excerpt Details */}
                      <div className="p-5 space-y-3">
                        <div className="flex gap-2 items-center text-[8px] font-mono text-gray-500 uppercase tracking-widest">
                          <span>{new Date(post.createdAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                          <span>•</span>
                          <span>{post.reading_time || 5} Min Read</span>
                        </div>
                        <h3 className="font-serif text-sm sm:text-base uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors line-clamp-2 leading-snug">
                          {post.title}
                        </h3>
                        <p className="text-[11px] text-gray-400 leading-relaxed line-clamp-3 font-light">
                          {post.excerpt}
                        </p>
                      </div>
                    </div>

                    {/* Author & Footer redirect */}
                    <div className="p-5 pt-0 border-t border-white/5 flex items-center justify-between mt-auto">
                      {post.author ? (
                        <div className="flex items-center gap-1.5">
                          {post.author.profile_photo && (
                            <img src={post.author.profile_photo} alt={post.author.name} className="h-5 w-5 rounded-none object-cover" />
                          )}
                          <span className="text-[8px] font-mono text-gray-400 uppercase tracking-wider">{post.author.name}</span>
                        </div>
                      ) : <div />}
                      <span className="inline-flex items-center gap-1 text-[9px] font-mono uppercase tracking-widest text-gold-400 group-hover:text-white transition-colors">
                        Read
                        <LucideIcon name="ArrowRight" size={11} />
                      </span>
                    </div>
                  </button>
                </motion.article>
              ))}
            </div>

            {/* Load More Button */}
            {visibleCount < regularPosts.length && (
              <div className="text-center pt-4">
                <button
                  onClick={() => setVisibleCount(prev => prev + 6)}
                  className="px-8 py-3 border border-white/20 text-white hover:border-gold-400 hover:text-gold-400 font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
                >
                  Load More Articles
                </button>
              </div>
            )}
          </div>
        )}
      </div>
    </main>
  );
};
