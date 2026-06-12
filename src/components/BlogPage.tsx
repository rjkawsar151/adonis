import React from 'react';
import { motion } from 'motion/react';
import { BlogPost } from '../types';
import { navigateTo } from '../navigation';
import { LucideIcon } from './LucideIcon';

interface BlogPageProps {
  posts: BlogPost[];
  slug?: string;
}

export const BlogPage: React.FC<BlogPageProps> = ({ posts, slug }) => {
  const publishedPosts = posts
    .filter(post => post.status === 'published')
    .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());
  const activePost = slug ? publishedPosts.find(post => post.slug === slug) : null;

  if (slug && activePost) {
    return (
      <main className="bg-salon-black text-white min-h-screen pt-28 pb-20">
        <article className="max-w-4xl mx-auto px-4 md:px-8">
          <button
            onClick={() => navigateTo('/blog')}
            className="mb-8 inline-flex items-center gap-2 text-[10px] font-mono uppercase tracking-widest text-gold-400 hover:text-white transition-colors"
          >
            <LucideIcon name="ArrowLeft" size={14} />
            Back to Blog
          </button>

          <motion.header
            initial={{ opacity: 0, y: 22 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.75 }}
            className="space-y-5 text-left"
          >
            <span className="text-[10px] font-mono text-gold-400 uppercase tracking-[0.3em]">Adonis Grooming Journal</span>
            <h1 className="font-serif text-3xl md:text-5xl uppercase tracking-wider leading-tight">{activePost.title}</h1>
            <p className="text-sm text-gray-400 leading-relaxed max-w-2xl">{activePost.excerpt}</p>
            <div className="text-[10px] font-mono uppercase tracking-widest text-gray-500">
              {new Date(activePost.createdAt).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
            </div>
          </motion.header>

          {activePost.coverImage && (
            <div className="mt-10 aspect-[16/8] overflow-hidden border border-gold-400/15 bg-salon-gray">
              <img src={activePost.coverImage} alt={activePost.title} className="h-full w-full object-cover brightness-90" />
            </div>
          )}

          <div
            className="blog-content mt-10 text-left text-gray-300"
            dangerouslySetInnerHTML={{ __html: activePost.contentHtml }}
          />

          <div className="mt-12 border-t border-white/10 pt-8 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div>
              <span className="text-[10px] font-mono uppercase tracking-widest text-gold-400">Ready for a sharper look?</span>
              <p className="text-xs text-gray-500 mt-1">Reserve your grooming session at Adonis.</p>
            </div>
            <button
              onClick={() => { navigateTo('/'); setTimeout(() => document.getElementById('booking-section')?.scrollIntoView({ behavior: 'smooth' }), 250); }}
              className="px-7 py-3 bg-[#32BBED] text-black font-serif text-xs font-bold uppercase tracking-widest hover:bg-[#b08d3c] transition-colors"
            >
              Book Appointment
            </button>
          </div>
        </article>
      </main>
    );
  }

  return (
    <main className="bg-salon-black text-white min-h-screen pt-28 pb-20">
      <section className="max-w-7xl mx-auto px-4 md:px-8 space-y-12">
        <motion.div
          initial={{ opacity: 0, y: 22 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.75 }}
          className="text-center max-w-3xl mx-auto space-y-4"
        >
          <span className="text-[10px] font-mono text-gold-400 uppercase tracking-[0.3em]">Adonis Editorial</span>
          <h1 className="font-serif text-3xl md:text-5xl uppercase tracking-wider text-white">Grooming Blog</h1>
          <p className="text-sm text-gray-400 leading-relaxed">
            Expert grooming guides, salon care advice, styling inspiration, and premium men’s wellness insights from Adonis.
          </p>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
          {publishedPosts.map((post, index) => (
            <motion.article
              key={post.id}
              initial={{ opacity: 0, y: 24, scale: 0.98 }}
              animate={{ opacity: 1, y: 0, scale: 1 }}
              transition={{ duration: 0.65, delay: index * 0.06 }}
              className="bg-salon-gray border border-white/5 hover:border-gold-400/30 transition-all group"
            >
              <button onClick={() => navigateTo(`/blog/${post.slug}`)} className="block w-full text-left">
                <div className="aspect-[16/10] overflow-hidden bg-salon-black">
                  <img src={post.coverImage} alt={post.title} className="h-full w-full object-cover brightness-90 group-hover:scale-[1.03] transition-transform duration-700" />
                </div>
                <div className="p-6 space-y-4">
                  <span className="text-[9px] font-mono uppercase tracking-widest text-gold-400">
                    {new Date(post.createdAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                  </span>
                  <h2 className="font-serif text-lg uppercase tracking-wider text-white group-hover:text-gold-400 transition-colors line-clamp-2">
                    {post.title}
                  </h2>
                  <p className="text-xs text-gray-400 leading-relaxed line-clamp-3">{post.excerpt}</p>
                  <span className="inline-flex items-center gap-2 text-[10px] font-mono uppercase tracking-widest text-gold-400">
                    Read Article
                    <LucideIcon name="ArrowRight" size={13} />
                  </span>
                </div>
              </button>
            </motion.article>
          ))}
        </div>
      </section>
    </main>
  );
};
