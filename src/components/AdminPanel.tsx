import React, { useState, useEffect } from 'react';
import { Service, Barber, Booking, SiteSettings, SmtpSettings, BlogPost } from '../types';
import { LucideIcon } from './LucideIcon';
import { navigateTo } from '../navigation';

interface AdminPanelProps {
  services: Service[];
  barbers: Barber[];
  settings: SiteSettings;
  smtp: SmtpSettings;
  bookings: Booking[];
  blogs: BlogPost[];
  onRefreshData: () => void;
}

export const AdminPanel: React.FC<AdminPanelProps> = ({
  services,
  barbers,
  settings,
  smtp,
  bookings,
  blogs,
  onRefreshData
}) => {
  // Authentication state
  const [passphrase, setPassphrase] = useState('');
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [authError, setAuthError] = useState('');

  // Active Admin Sub-tab
  const [activeTab, setActiveTab] = useState<'services' | 'barbers' | 'blogs' | 'settings' | 'smtp'>('services');

  // Form States for CRUD
  const [editingService, setEditingService] = useState<Partial<Service> | null>(null);
  const [isAddingService, setIsAddingService] = useState(false);

  const [editingBarber, setEditingBarber] = useState<Partial<Barber> | null>(null);
  const [isAddingBarber, setIsAddingBarber] = useState(false);
  const [uploadingImage, setUploadingImage] = useState(false);

  const [editingBlog, setEditingBlog] = useState<Partial<BlogPost> | null>(null);
  const [isAddingBlog, setIsAddingBlog] = useState(false);
  const [blogImageUrl, setBlogImageUrl] = useState('');

  // Form States for Settings
  const [siteForm, setSiteForm] = useState<SiteSettings>({ ...settings });
  const [smtpForm, setSmtpForm] = useState<SmtpSettings>({ ...smtp });
  const [smtpTestResult, setSmtpTestResult] = useState<{ success: boolean; message: string } | null>(null);
  const [smtpTesting, setSmtpTesting] = useState(false);

  // Update form states when props change
  useEffect(() => {
    setSiteForm({ ...settings });
  }, [settings]);

  useEffect(() => {
    setSmtpForm({ ...smtp });
  }, [smtp]);

  // Auth Submit
  const handleAuthSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (passphrase === 'adonis-admin') {
      setIsAuthenticated(true);
      setAuthError('');
    } else {
      setAuthError('Authentication denied. Invalid credentials registry.');
    }
  };

  // Service Operations
  const handleSaveService = async (service: Partial<Service>) => {
    const isEdit = !!service.id;
    const url = isEdit ? `/api/services/${service.id}` : '/api/services';
    const method = isEdit ? 'PUT' : 'POST';

    try {
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(service)
      });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
        setEditingService(null);
        setIsAddingService(false);
      }
    } catch (err) {
      console.error(err);
      alert('Failed to save service.');
    }
  };

  const handleDeleteService = async (id: string) => {
    if (!confirm('Are you sure you want to delete this service? This cannot be undone.')) return;
    try {
      const response = await fetch(`/api/services/${id}`, { method: 'DELETE' });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
      }
    } catch (err) {
      console.error(err);
    }
  };

  // Barber Operations
  const handleSaveBarber = async (barber: Partial<Barber>) => {
    const isEdit = !!barber.id;
    const url = isEdit ? `/api/barbers/${barber.id}` : '/api/barbers';
    const method = isEdit ? 'PUT' : 'POST';

    try {
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(barber)
      });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
        setEditingBarber(null);
        setIsAddingBarber(false);
      }
    } catch (err) {
      console.error(err);
      alert('Failed to save barber.');
    }
  };

  const handleDeleteBarber = async (id: string) => {
    if (!confirm('Are you sure you want to delete this master barber?')) return;
    try {
      const response = await fetch(`/api/barbers/${id}`, { method: 'DELETE' });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
      }
    } catch (err) {
      console.error(err);
    }
  };

  const createSlug = (title: string) => {
    return title.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  };

  const handleSaveBlog = async (blog: Partial<BlogPost>) => {
    const title = blog.title || 'Untitled Blog';
    const normalizedBlog = {
      ...blog,
      title,
      slug: blog.slug || createSlug(title),
      id: blog.id || createSlug(title),
      status: blog.status || 'draft',
      contentHtml: blog.contentHtml || '',
      coverImage: blog.coverImage || '',
      excerpt: blog.excerpt || '',
      seoTitle: blog.seoTitle || title,
      seoDescription: blog.seoDescription || blog.excerpt || ''
    };
    const isEdit = !!blog.id;
    const url = isEdit ? `/api/blogs/${blog.id}` : '/api/blogs';
    const method = isEdit ? 'PUT' : 'POST';

    try {
      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(normalizedBlog)
      });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
        setEditingBlog(null);
        setIsAddingBlog(false);
      } else {
        alert(data.error || 'Failed to save blog post.');
      }
    } catch (err) {
      console.error(err);
      alert('Failed to save blog post.');
    }
  };

  const handleDeleteBlog = async (id: string) => {
    if (!confirm('Are you sure you want to delete this blog post?')) return;
    try {
      const response = await fetch(`/api/blogs/${id}`, { method: 'DELETE' });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
      }
    } catch (err) {
      console.error(err);
    }
  };

  const runBlogCommand = (command: string, value?: string) => {
    document.execCommand(command, false, value);
    const editor = document.getElementById('blog-content-editor');
    if (editor) {
      setEditingBlog(prev => ({ ...(prev || {}), contentHtml: editor.innerHTML }));
    }
  };

  const insertBlogImage = (url: string) => {
    if (!url.trim()) return;
    runBlogCommand('insertHTML', `<img src="${url.trim()}" alt="" />`);
    setBlogImageUrl('');
  };

  const handleBlogImageUpload = async (e: React.ChangeEvent<HTMLInputElement>, target: 'cover' | 'content') => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploadingImage(true);
    const formData = new FormData();
    formData.append('portrait', file);

    try {
      const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        if (target === 'cover') {
          setEditingBlog(prev => ({ ...(prev || {}), coverImage: data.url }));
        } else {
          insertBlogImage(data.url);
        }
      } else {
        alert('Image upload failed.');
      }
    } catch (err) {
      console.error(err);
      alert('Error uploading image.');
    } finally {
      setUploadingImage(false);
      e.target.value = '';
    }
  };

  // Image upload handler
  const handlePortraitUpload = async (e: React.ChangeEvent<HTMLInputElement>, forEditing: boolean) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploadingImage(true);
    const formData = new FormData();
    formData.append('portrait', file);

    try {
      const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        if (forEditing && editingBarber) {
          setEditingBarber({ ...editingBarber, portraitUrl: data.url });
        } else if (!forEditing) {
          setEditingBarber((prev) => ({ ...(prev || {}), portraitUrl: data.url }));
        }
      } else {
        alert('Image upload failed.');
      }
    } catch (err) {
      console.error(err);
      alert('Error uploading image.');
    } finally {
      setUploadingImage(false);
    }
  };

  // Settings updates
  const handleSaveSettings = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/settings', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(siteForm)
      });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
        alert('Site configuration stored successfully.');
      }
    } catch (err) {
      console.error(err);
      alert('Error storing settings.');
    }
  };

  const handleSaveSmtp = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const response = await fetch('/api/smtp', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(smtpForm)
      });
      const data = await response.json();
      if (data.success) {
        onRefreshData();
        alert('SMTP configuration saved successfully.');
      }
    } catch (err) {
      console.error(err);
      alert('Error saving SMTP settings.');
    }
  };

  const handleTestSmtp = async () => {
    setSmtpTesting(true);
    setSmtpTestResult(null);
    try {
      const response = await fetch('/api/smtp/test', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(smtpForm)
      });
      const data = await response.json();
      setSmtpTestResult({
        success: data.success,
        message: data.success ? data.message : (data.error || 'SMTP connection failed.')
      });
    } catch (err: any) {
      setSmtpTestResult({ success: false, message: err.message || 'Could not reach server.' });
    } finally {
      setSmtpTesting(false);
    }
  };

  // Helper
  const getBranchName = (bId: string) => {
    if (bId === 'gulshan') return 'Gulshan';
    if (bId === 'bashundhara') return 'Bashundhara';
    return bId;
  };

  const getServiceName = (sId: string) => {
    return services.find(s => s.id === sId)?.name || sId;
  };

  const getBarberName = (baId: string) => {
    return barbers.find(b => b.id === baId)?.name || baId;
  };

  // Auth Guard Screen
  if (!isAuthenticated) {
    return (
      <div className="min-h-screen bg-salon-black text-white flex items-center justify-center px-4 py-28 relative">
        <div className="absolute top-[20%] left-[20%] h-[300px] w-[300px] rounded-full bg-gold-400/5 blur-[90px] pointer-events-none"></div>
        <div className="absolute bottom-[20%] right-[20%] h-[300px] w-[300px] rounded-full bg-gold-400/5 blur-[90px] pointer-events-none"></div>

        <div className="w-full max-w-md bg-salon-gray border border-gold-400/25 p-8 shadow-2xl relative">
          <div className="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-gold-400 to-transparent"></div>

          <div className="text-center space-y-3 mb-8">
            <LucideIcon name="Crown" className="text-gold-400 mx-auto" size={36} />
            <h1 className="font-serif text-2xl uppercase tracking-widest text-white">Adonis Command</h1>
            <p className="text-[10px] text-gray-500 uppercase tracking-widest">Administrative Authentication Required</p>
          </div>

          <form onSubmit={handleAuthSubmit} className="space-y-6">
            <div>
              <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-2">Security Passphrase</label>
              <input
                type="password"
                required
                value={passphrase}
                onChange={(e) => setPassphrase(e.target.value)}
                placeholder="e.g. adonis-admin"
                className="w-full bg-salon-black text-white text-sm border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors placeholder:text-gray-700"
              />
            </div>

            {authError && (
              <div className="p-3 bg-red-950/25 border border-red-500/20 text-red-400 text-[11px] font-mono flex items-center gap-2">
                <LucideIcon name="ShieldAlert" size={14} className="shrink-0" />
                <span>{authError}</span>
              </div>
            )}

            <button
              type="submit"
              className="w-full py-3 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer"
            >
              Unlock Console
            </button>
          </form>

          <div className="mt-6 text-center">
            <button
              onClick={() => navigateTo('/')}
              className="text-[10px] font-mono uppercase tracking-widest text-gray-500 hover:text-white transition-colors"
            >
              ← Back To Front Lounge
            </button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-salon-black text-white py-28 relative">
      <div className="max-w-7xl mx-auto px-4 md:px-8 space-y-8 relative z-10">

        {/* Admin Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-6">
          <div className="text-left">
            <h1 className="font-serif text-3xl uppercase tracking-wider text-white">Adonis Dashboard</h1>
            <p className="text-[10px] text-gold-400 font-mono uppercase tracking-widest mt-1">
              Active Control Session • Status: Authenticated
            </p>
          </div>

          <div className="flex gap-4">
            <button
              onClick={() => navigateTo('/')}
              className="px-6 py-2 border border-white/10 text-white hover:border-gold-400/50 text-[10px] font-mono uppercase tracking-widest transition-colors cursor-pointer"
            >
              Front Website
            </button>
            <button
              onClick={() => { setIsAuthenticated(false); setPassphrase(''); }}
              className="px-6 py-2 bg-red-950/20 border border-red-500/20 text-red-400 hover:border-red-500/50 hover:bg-red-950/40 text-[10px] font-mono uppercase tracking-widest transition-colors cursor-pointer"
            >
              Lock Console
            </button>
          </div>
        </div>

        {/* Tab Selection */}
        <div className="flex flex-wrap border-b border-white/5 pb-2">
          {[
            { id: 'services', label: 'Services Manager', icon: 'Scissors' },
            { id: 'barbers', label: 'Barbers Manager', icon: 'Users' },
            { id: 'blogs', label: 'Blog Writer', icon: 'FileText' },
            { id: 'settings', label: 'Site Settings', icon: 'Settings' },
            { id: 'smtp', label: 'SMTP Configuration', icon: 'Mail' }
          ].map(tab => (
            <button
              key={tab.id}
              onClick={() => {
                setActiveTab(tab.id as any);
                setEditingService(null);
                setIsAddingService(false);
                setEditingBarber(null);
                setIsAddingBarber(false);
                setEditingBlog(null);
                setIsAddingBlog(false);
              }}
              className={`px-6 py-3.5 text-[11px] font-mono uppercase tracking-widest flex items-center gap-2 border-b-2 transition-all cursor-pointer ${activeTab === tab.id
                  ? 'border-gold-400 text-gold-400 bg-salon-gray/45'
                  : 'border-transparent text-gray-400 hover:text-white hover:bg-white/5'
                }`}
            >
              <LucideIcon name={tab.icon} size={14} />
              {tab.label}
            </button>
          ))}
        </div>

        {/* Sub-tab Views */}
        <div className="bg-salon-gray border border-white/5 p-6 md:p-8">

          {/* Booking Logs removed */}

          {/* 2. SERVICES CRUD */}
          {activeTab === 'services' && (
            <div className="space-y-6">
              <div className="flex items-center justify-between">
                <div className="text-left space-y-1">
                  <h3 className="font-serif text-lg uppercase tracking-wider text-white">Services Catalog Manager</h3>
                  <p className="text-[10px] text-gray-500 uppercase tracking-widest">Add or modify luxury grooming services available for booking</p>
                </div>
                {!editingService && !isAddingService && (
                  <button
                    onClick={() => {
                      setIsAddingService(true);
                      setEditingService({ name: '', description: '', durationMin: 45, priceBDT: 1500, category: 'hair', icon: 'Scissors' });
                    }}
                    className="px-5 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer"
                  >
                    Add Service
                  </button>
                )}
              </div>

              {/* Service Editor Modal/Form */}
              {(isAddingService || editingService) && editingService && (
                <div className="p-6 border border-gold-400/20 bg-salon-black space-y-6 max-w-2xl text-left">
                  <h4 className="font-serif text-sm uppercase tracking-wider text-gold-400 pb-2 border-b border-white/5">
                    {isAddingService ? 'Add Premium Service' : 'Edit Service details'}
                  </h4>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Service Name</label>
                      <input
                        type="text"
                        value={editingService.name || ''}
                        onChange={(e) => setEditingService({ ...editingService, name: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Category</label>
                      <select
                        value={editingService.category || 'hair'}
                        onChange={(e) => setEditingService({ ...editingService, category: e.target.value as any })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      >
                        <option value="hair">Precision Cuts (hair)</option>
                        <option value="beard">Beards & Shaves (beard)</option>
                        <option value="spa">Therapies & Spas (spa)</option>
                        <option value="pack">VIP Packages (pack)</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Price (BDT)</label>
                      <input
                        type="number"
                        value={editingService.priceBDT || 0}
                        onChange={(e) => setEditingService({ ...editingService, priceBDT: parseInt(e.target.value) || 0 })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Duration (Mins)</label>
                      <input
                        type="number"
                        value={editingService.durationMin || 0}
                        onChange={(e) => setEditingService({ ...editingService, durationMin: parseInt(e.target.value) || 0 })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Lucide Icon name</label>
                      <input
                        type="text"
                        value={editingService.icon || 'Scissors'}
                        onChange={(e) => setEditingService({ ...editingService, icon: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                      <span className="text-[9px] text-gray-550 block mt-1">Options: Scissors, Sparkles, Smile, Flame, Flower, Crown, ShieldAlert, UserCheck</span>
                    </div>
                    <div className="md:col-span-2">
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Service Description</label>
                      <textarea
                        rows={3}
                        value={editingService.description || ''}
                        onChange={(e) => setEditingService({ ...editingService, description: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                      />
                    </div>
                  </div>

                  <div className="flex gap-4">
                    <button
                      onClick={() => handleSaveService(editingService)}
                      className="px-6 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      Save Service
                    </button>
                    <button
                      onClick={() => { setEditingService(null); setIsAddingService(false); }}
                      className="px-6 py-2.5 border border-white/10 text-white hover:border-gold-400/50 text-[10px] font-serif uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              )}

              {/* Service List Table */}
              {!editingService && !isAddingService && (
                <div className="overflow-x-auto">
                  <table className="w-full text-xs text-left border-collapse">
                    <thead>
                      <tr className="border-b border-white/10 text-[10px] font-mono text-gold-400 uppercase tracking-wider">
                        <th className="py-3 px-4">Icon</th>
                        <th className="py-3 px-4">Name</th>
                        <th className="py-3 px-4">Category</th>
                        <th className="py-3 px-4">Rate (BDT)</th>
                        <th className="py-3 px-4">Duration</th>
                        <th className="py-3 px-4">Description</th>
                        <th className="py-3 px-4 text-right">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                      {services.map((s) => (
                        <tr key={s.id} className="hover:bg-white/[0.02] text-gray-300">
                          <td className="py-3.5 px-4 text-gold-400">
                            <LucideIcon name={s.icon} size={16} />
                          </td>
                          <td className="py-3.5 px-4 font-serif uppercase font-semibold text-white">{s.name}</td>
                          <td className="py-3.5 px-4 font-mono">{s.category}</td>
                          <td className="py-3.5 px-4 font-bold text-white">৳{s.priceBDT}</td>
                          <td className="py-3.5 px-4 font-mono">{s.durationMin} mins</td>
                          <td className="py-3.5 px-4 text-gray-400 max-w-xs truncate" title={s.description}>
                            {s.description}
                          </td>
                          <td className="py-3.5 px-4 text-right space-x-2">
                            <button
                              onClick={() => setEditingService(s)}
                              className="text-[10px] font-mono text-gold-400 hover:underline cursor-pointer"
                            >
                              Edit
                            </button>
                            <button
                              onClick={() => handleDeleteService(s.id)}
                              className="text-[10px] font-mono text-red-400 hover:underline cursor-pointer"
                            >
                              Delete
                            </button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          )}

          {/* 3. BARBERS CRUD */}
          {activeTab === 'barbers' && (
            <div className="space-y-6">
              <div className="flex items-center justify-between">
                <div className="text-left space-y-1">
                  <h3 className="font-serif text-lg uppercase tracking-wider text-white">Master Barbers Manager</h3>
                  <p className="text-[10px] text-gray-500 uppercase tracking-widest">Update portfolios, ratings, bio descriptions, and upload portraits</p>
                </div>
                {!editingBarber && !isAddingBarber && (
                  <button
                    onClick={() => {
                      setIsAddingBarber(true);
                      setEditingBarber({ name: '', specialty: '', bio: '', experienceYears: 5, rating: 4.8, portraitUrl: '' });
                    }}
                    className="px-5 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer"
                  >
                    Add Stylist
                  </button>
                )}
              </div>

              {/* Barber Editor Form */}
              {(isAddingBarber || editingBarber) && editingBarber && (
                <div className="p-6 border border-gold-400/20 bg-salon-black space-y-6 max-w-2xl text-left">
                  <h4 className="font-serif text-sm uppercase tracking-wider text-gold-400 pb-2 border-b border-white/5">
                    {isAddingBarber ? 'Add Stylist Biography' : 'Modify Stylist details'}
                  </h4>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Stylist Full Name</label>
                      <input
                        type="text"
                        value={editingBarber.name || ''}
                        onChange={(e) => setEditingBarber({ ...editingBarber, name: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Specialty & Artistry</label>
                      <input
                        type="text"
                        value={editingBarber.specialty || ''}
                        onChange={(e) => setEditingBarber({ ...editingBarber, specialty: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Experience (Years)</label>
                      <input
                        type="number"
                        value={editingBarber.experienceYears || 0}
                        onChange={(e) => setEditingBarber({ ...editingBarber, experienceYears: parseInt(e.target.value) || 0 })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Rating Rating (e.g. 4.9)</label>
                      <input
                        type="number"
                        step="0.1"
                        value={editingBarber.rating || 0}
                        onChange={(e) => setEditingBarber({ ...editingBarber, rating: parseFloat(e.target.value) || 0 })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                      />
                    </div>

                    {/* Image Portrait Upload */}
                    <div className="md:col-span-2 space-y-2.5">
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Portrait Image</label>
                      <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <input
                          type="text"
                          value={editingBarber.portraitUrl || ''}
                          onChange={(e) => setEditingBarber({ ...editingBarber, portraitUrl: e.target.value })}
                          placeholder="Portrait URL or Upload Image below"
                          className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                        />
                        <div className="relative shrink-0">
                          <input
                            type="file"
                            accept="image/*"
                            onChange={(e) => handlePortraitUpload(e, !isAddingBarber)}
                            className="absolute inset-0 opacity-0 w-full cursor-pointer z-10"
                          />
                          <button
                            type="button"
                            className="px-4 py-2.5 bg-salon-gray hover:bg-white/5 border border-white/10 text-white text-[10px] font-mono uppercase tracking-widest transition-colors flex items-center gap-1"
                          >
                            <LucideIcon name="Upload" size={12} />
                            {uploadingImage ? 'Uploading...' : 'Browse Image'}
                          </button>
                        </div>
                      </div>
                      {editingBarber.portraitUrl && (
                        <div className="h-16 w-16 border border-white/10 overflow-hidden bg-black">
                          <img src={editingBarber.portraitUrl} alt="Preview" className="h-full w-full object-cover" />
                        </div>
                      )}
                    </div>

                    <div className="md:col-span-2">
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Bio Biography</label>
                      <textarea
                        rows={3}
                        value={editingBarber.bio || ''}
                        onChange={(e) => setEditingBarber({ ...editingBarber, bio: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                      />
                    </div>
                  </div>

                  <div className="flex gap-4">
                    <button
                      onClick={() => handleSaveBarber(editingBarber)}
                      className="px-6 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      Save Stylist
                    </button>
                    <button
                      onClick={() => { setEditingBarber(null); setIsAddingBarber(false); }}
                      className="px-6 py-2.5 border border-white/10 text-white hover:border-gold-400/50 text-[10px] font-serif uppercase tracking-widest transition-colors cursor-pointer"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              )}

              {/* Barber List Grid */}
              {!editingBarber && !isAddingBarber && (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                  {barbers.map((b) => (
                    <div key={b.id} className="bg-salon-black border border-white/5 p-4 flex gap-4 hover:border-gold-400/25 transition-all">
                      <div className="h-20 w-16 shrink-0 overflow-hidden bg-salon-gray border border-white/5">
                        <img src={b.portraitUrl} alt={b.name} className="h-full w-full object-cover grayscale" />
                      </div>
                      <div className="flex flex-col justify-between flex-1 min-w-0">
                        <div>
                          <div className="flex items-start justify-between gap-1.5">
                            <h4 className="font-serif text-sm uppercase text-white truncate">{b.name}</h4>
                            <span className="text-[9px] text-yellow-450 font-mono">★ {b.rating}</span>
                          </div>
                          <p className="text-[10px] font-mono text-gold-400 uppercase truncate mt-0.5">{b.specialty}</p>
                          <p className="text-[9px] text-gray-500 mt-1 line-clamp-1">{b.bio}</p>
                        </div>
                        <div className="flex justify-end gap-3 text-[10px] font-mono mt-2">
                          <button
                            onClick={() => setEditingBarber(b)}
                            className="text-gold-400 hover:underline cursor-pointer"
                          >
                            Edit
                          </button>
                          <button
                            onClick={() => handleDeleteBarber(b.id)}
                            className="text-red-400 hover:underline cursor-pointer"
                          >
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* 4. BLOG CMS */}
          {activeTab === 'blogs' && (
            <div className="space-y-6">
              <div className="flex items-center justify-between gap-4">
                <div className="text-left space-y-1">
                  <h3 className="font-serif text-lg uppercase tracking-wider text-white">Blog Writing Studio</h3>
                  <p className="text-[10px] text-gray-500 uppercase tracking-widest">Create SEO powered salon articles with rich formatting and images</p>
                </div>
                {!editingBlog && !isAddingBlog && (
                  <button
                    onClick={() => {
                      const now = new Date().toISOString();
                      setIsAddingBlog(true);
                      setEditingBlog({
                        title: '',
                        slug: '',
                        excerpt: '',
                        coverImage: '',
                        contentHtml: '<h2>Article Headline</h2><p>Start writing your salon blog content here.</p>',
                        seoTitle: '',
                        seoDescription: '',
                        status: 'draft',
                        createdAt: now,
                        updatedAt: now
                      });
                    }}
                    className="px-5 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer"
                  >
                    Add Blog
                  </button>
                )}
              </div>

              {(isAddingBlog || editingBlog) && editingBlog && (
                <div className="p-6 border border-gold-400/20 bg-salon-black space-y-6 text-left">
                  <div className="flex items-center justify-between gap-4 border-b border-white/5 pb-3">
                    <h4 className="font-serif text-sm uppercase tracking-wider text-gold-400">
                      {isAddingBlog ? 'Write New Blog Post' : 'Edit Blog Post'}
                    </h4>
                    <select
                      value={editingBlog.status || 'draft'}
                      onChange={(e) => setEditingBlog({ ...editingBlog, status: e.target.value as BlogPost['status'] })}
                      className="bg-salon-gray text-white text-xs border border-white/10 px-3 py-2 focus:outline-none focus:border-gold-400"
                    >
                      <option value="draft">Draft</option>
                      <option value="published">Published</option>
                    </select>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Headline</label>
                      <input
                        type="text"
                        value={editingBlog.title || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, title: e.target.value, slug: editingBlog.slug || createSlug(e.target.value) })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">URL Slug</label>
                      <input
                        type="text"
                        value={editingBlog.slug || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, slug: createSlug(e.target.value) })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400"
                      />
                    </div>
                    <div className="md:col-span-2">
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Short Excerpt</label>
                      <textarea
                        rows={2}
                        value={editingBlog.excerpt || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, excerpt: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 resize-none"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Cover Image URL</label>
                      <input
                        type="text"
                        value={editingBlog.coverImage || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, coverImage: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Upload Cover Image</label>
                      <input
                        type="file"
                        accept="image/*"
                        onChange={(e) => handleBlogImageUpload(e, 'cover')}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2 file:mr-3 file:border-0 file:bg-gold-400 file:text-black file:px-3 file:py-1 file:text-[10px]"
                      />
                    </div>
                  </div>

                  <div className="space-y-3">
                    <span className="text-[9px] font-mono uppercase tracking-widest text-gray-500 block">Content Editor</span>
                    <div className="flex flex-wrap gap-2 border border-white/10 bg-salon-gray p-2">
                      {['H2', 'H3', 'H4', 'H5', 'H6'].map(tag => (
                        <button key={tag} type="button" onClick={() => runBlogCommand('formatBlock', tag)} className="px-3 py-1.5 bg-salon-black text-white hover:text-gold-400 text-[10px] font-mono uppercase border border-white/10">
                          {tag}
                        </button>
                      ))}
                      <button type="button" onClick={() => runBlogCommand('bold')} className="px-3 py-1.5 bg-salon-black text-white hover:text-gold-400 text-[10px] font-bold border border-white/10">B</button>
                      <button type="button" onClick={() => runBlogCommand('underline')} className="px-3 py-1.5 bg-salon-black text-white hover:text-gold-400 text-[10px] underline border border-white/10">U</button>
                      <button type="button" onClick={() => runBlogCommand('insertUnorderedList')} className="px-3 py-1.5 bg-salon-black text-white hover:text-gold-400 text-[10px] border border-white/10">Bullet</button>
                      <button type="button" onClick={() => runBlogCommand('insertOrderedList')} className="px-3 py-1.5 bg-salon-black text-white hover:text-gold-400 text-[10px] border border-white/10">Number</button>
                      <input type="color" onChange={(e) => runBlogCommand('foreColor', e.target.value)} className="h-8 w-10 bg-salon-black border border-white/10" title="Text color" />
                      <input
                        type="text"
                        value={blogImageUrl}
                        onChange={(e) => setBlogImageUrl(e.target.value)}
                        placeholder="Image URL"
                        className="min-w-[180px] flex-1 bg-salon-black text-white text-[10px] border border-white/10 px-3 py-1.5 focus:outline-none focus:border-gold-400"
                      />
                      <button type="button" onClick={() => insertBlogImage(blogImageUrl)} className="px-3 py-1.5 bg-salon-black text-gold-400 text-[10px] border border-gold-400/30">Insert Image</button>
                      <label className="px-3 py-1.5 bg-salon-black text-gold-400 text-[10px] border border-gold-400/30 cursor-pointer">
                        Upload Image
                        <input type="file" accept="image/*" onChange={(e) => handleBlogImageUpload(e, 'content')} className="hidden" />
                      </label>
                    </div>
                    <div
                      id="blog-content-editor"
                      contentEditable
                      suppressContentEditableWarning
                      onInput={(e) => setEditingBlog({ ...editingBlog, contentHtml: e.currentTarget.innerHTML })}
                      dangerouslySetInnerHTML={{ __html: editingBlog.contentHtml || '' }}
                      className="blog-content min-h-[320px] bg-salon-gray text-gray-300 border border-white/10 p-5 focus:outline-none focus:border-gold-400 overflow-auto"
                    />
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-white/5 pt-5">
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SEO Title</label>
                      <input
                        type="text"
                        value={editingBlog.seoTitle || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, seoTitle: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400"
                      />
                    </div>
                    <div>
                      <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SEO Description</label>
                      <textarea
                        rows={2}
                        value={editingBlog.seoDescription || ''}
                        onChange={(e) => setEditingBlog({ ...editingBlog, seoDescription: e.target.value })}
                        className="w-full bg-salon-gray text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 resize-none"
                      />
                    </div>
                  </div>

                  <div className="flex gap-4">
                    <button onClick={() => handleSaveBlog(editingBlog)} disabled={uploadingImage} className="px-6 py-2.5 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-[10px] font-bold uppercase tracking-widest transition-colors cursor-pointer disabled:opacity-50">
                      Save Blog Post
                    </button>
                    <button onClick={() => { setEditingBlog(null); setIsAddingBlog(false); }} className="px-6 py-2.5 border border-white/10 text-white hover:border-gold-400/50 text-[10px] font-serif uppercase tracking-widest transition-colors cursor-pointer">
                      Cancel
                    </button>
                  </div>
                </div>
              )}

              {!editingBlog && !isAddingBlog && (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
                  {blogs.map((post) => (
                    <div key={post.id} className="bg-salon-black border border-white/5 p-4 flex gap-4 hover:border-gold-400/25 transition-all">
                      <div className="h-24 w-28 shrink-0 overflow-hidden bg-salon-gray border border-white/5">
                        <img src={post.coverImage} alt={post.title} className="h-full w-full object-cover grayscale" />
                      </div>
                      <div className="flex flex-col justify-between flex-1 min-w-0">
                        <div>
                          <div className="flex items-start justify-between gap-2">
                            <h4 className="font-serif text-sm uppercase text-white line-clamp-1">{post.title}</h4>
                            <span className={`text-[9px] font-mono uppercase ${post.status === 'published' ? 'text-green-400' : 'text-yellow-400'}`}>{post.status}</span>
                          </div>
                          <p className="text-[10px] text-gold-400 font-mono truncate mt-0.5">/blog/{post.slug}</p>
                          <p className="text-[9px] text-gray-500 mt-1 line-clamp-2">{post.excerpt}</p>
                        </div>
                        <div className="flex justify-end gap-3 text-[10px] font-mono mt-3">
                          <button onClick={() => setEditingBlog(post)} className="text-gold-400 hover:underline cursor-pointer">Edit</button>
                          <button onClick={() => handleDeleteBlog(post.id)} className="text-red-400 hover:underline cursor-pointer">Delete</button>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* 4. SITE SETTINGS EDITOR */}
          {activeTab === 'settings' && (
            <form onSubmit={handleSaveSettings} className="space-y-6 text-left max-w-3xl">
              <div className="space-y-1">
                <h3 className="font-serif text-lg uppercase tracking-wider text-white">General Site Settings</h3>
                <p className="text-[10px] text-gray-500 uppercase tracking-widest">Customize brand descriptions, taglines, contact paths, and opening hours</p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Brand Name</label>
                  <input
                    type="text"
                    value={siteForm.brandName || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, brandName: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Brand Tagline</label>
                  <input
                    type="text"
                    value={siteForm.brandSubtitle || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, brandSubtitle: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Hero Banner Title</label>
                  <input
                    type="text"
                    value={siteForm.heroTitle || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, heroTitle: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Hero Background image link</label>
                  <input
                    type="text"
                    value={siteForm.heroBg || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, heroBg: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Hero Banner Subtitle</label>
                  <textarea
                    rows={2}
                    value={siteForm.heroSubtitle || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, heroSubtitle: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                  />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">About Us Brand Story</label>
                  <textarea
                    rows={3}
                    value={siteForm.aboutStory || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, aboutStory: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                  />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">About Us Brand Description</label>
                  <textarea
                    rows={4}
                    value={siteForm.aboutDescription || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, aboutDescription: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors resize-none"
                  />
                </div>

                <div className="border-t border-white/5 pt-4 md:col-span-2">
                  <span className="text-[10px] font-mono text-gold-400 uppercase tracking-widest block mb-3 font-semibold">Contact & Hours</span>
                </div>

                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Open Days</label>
                  <input
                    type="text"
                    value={siteForm.openHoursDays || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, openHoursDays: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Open Hours</label>
                  <input
                    type="text"
                    value={siteForm.openHoursTime || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, openHoursTime: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Contact Email</label>
                  <input
                    type="email"
                    value={siteForm.contactEmail || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, contactEmail: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Phone Numbers (comma separated)</label>
                  <input
                    type="text"
                    value={siteForm.phoneNumbers?.join(', ') || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, phoneNumbers: e.target.value.split(',').map(s => s.trim()) })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>

                <div className="border-t border-white/5 pt-4 md:col-span-2">
                  <span className="text-[10px] font-mono text-gold-400 uppercase tracking-widest block mb-3 font-semibold">Social Links</span>
                </div>

                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Facebook URL</label>
                  <input
                    type="url"
                    value={siteForm.facebookUrl || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, facebookUrl: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Instagram URL</label>
                  <input
                    type="url"
                    value={siteForm.instagramUrl || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, instagramUrl: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">WhatsApp URL</label>
                  <input
                    type="url"
                    value={siteForm.whatsappUrl || ''}
                    onChange={(e) => setSiteForm({ ...siteForm, whatsappUrl: e.target.value })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
              </div>

              <div className="pt-4">
                <button
                  type="submit"
                  className="px-8 py-3 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-colors cursor-pointer"
                >
                  Save General Settings
                </button>
              </div>
            </form>
          )}

          {/* 5. SMTP SETTINGS */}
          {activeTab === 'smtp' && (
            <form onSubmit={handleSaveSmtp} className="space-y-6 text-left max-w-xl">
              <div className="space-y-1">
                <h3 className="font-serif text-lg uppercase tracking-wider text-white">SMTP Mail Configuration</h3>
                <p className="text-[10px] text-gray-500 uppercase tracking-widest">Setup your SMTP mail server settings to enable confirmation emails for clients and notifications for admin</p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SMTP Host Server</label>
                  <input
                    type="text"
                    required
                    value={smtpForm.host || ''}
                    onChange={(e) => setSmtpForm({ ...smtpForm, host: e.target.value })}
                    placeholder="e.g. smtp.gmail.com"
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SMTP Port</label>
                  <input
                    type="number"
                    required
                    value={smtpForm.port || 587}
                    onChange={(e) => setSmtpForm({ ...smtpForm, port: parseInt(e.target.value) || 587 })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Secure Protocol (SSL/TLS)</label>
                  <select
                    value={smtpForm.secure ? 'true' : 'false'}
                    onChange={(e) => setSmtpForm({ ...smtpForm, secure: e.target.value === 'true' })}
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  >
                    <option value="false">false (Port 587 / STARTTLS)</option>
                    <option value="true">true (Port 465 / SSL)</option>
                  </select>
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SMTP Username</label>
                  <input
                    type="text"
                    required
                    value={smtpForm.user || ''}
                    onChange={(e) => setSmtpForm({ ...smtpForm, user: e.target.value })}
                    placeholder="e.g. user@gmail.com"
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div className="md:col-span-2">
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">SMTP Password</label>
                  <input
                    type="password"
                    required
                    value={smtpForm.pass || ''}
                    onChange={(e) => setSmtpForm({ ...smtpForm, pass: e.target.value })}
                    placeholder="Application password"
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div className="md:col-span-2 border-t border-white/5 pt-4">
                  <span className="text-[10px] font-mono text-gold-400 uppercase tracking-widest block mb-3 font-semibold">Transmission Envelopes</span>
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Send Outbound From (fromEmail)</label>
                  <input
                    type="email"
                    required
                    value={smtpForm.fromEmail || ''}
                    onChange={(e) => setSmtpForm({ ...smtpForm, fromEmail: e.target.value })}
                    placeholder="e.g. noreply@adonis.bd"
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
                <div>
                  <label className="block text-[9px] font-mono uppercase tracking-widest text-gray-500 mb-1">Admin Notification Emails (comma separated)</label>
                  <input
                    type="text"
                    required
                    value={smtpForm.adminEmails || ''}
                    onChange={(e) => setSmtpForm({ ...smtpForm, adminEmails: e.target.value })}
                    placeholder="e.g. admin1@adonis.bd, admin2@adonis.bd"
                    className="w-full bg-salon-black text-white text-xs border border-white/10 px-3 py-2.5 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
              </div>

              <div className="pt-4 flex flex-col sm:flex-row gap-3">
                <button
                  type="submit"
                  className="px-8 py-3 bg-gold-400 hover:bg-gold-500 text-salon-black font-serif text-xs font-bold uppercase tracking-widest transition-colors cursor-pointer"
                >
                  Save SMTP Configuration
                </button>
                <button
                  type="button"
                  onClick={handleTestSmtp}
                  disabled={smtpTesting}
                  className="px-8 py-3 border border-gold-400/40 text-gold-400 hover:bg-gold-400/10 font-serif text-xs font-bold uppercase tracking-widest transition-colors cursor-pointer disabled:opacity-50"
                >
                  {smtpTesting ? 'Testing...' : 'Test Connection'}
                </button>
              </div>

              {smtpTestResult && (
                <div className={`p-4 border text-xs font-mono ${smtpTestResult.success
                    ? 'border-green-500/40 bg-green-500/5 text-green-400'
                    : 'border-red-500/40 bg-red-500/5 text-red-400'
                  }`}>
                  {smtpTestResult.success ? '✓' : '✗'} {smtpTestResult.message}
                </div>
              )}
            </form>
          )}

        </div>

      </div>
    </div>
  );
};
