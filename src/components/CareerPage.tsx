import React, { useState, useEffect } from 'react';
import { navigateTo } from '../navigation';
import { LucideIcon } from './LucideIcon';

interface Job {
  id: number;
  title: string;
  slug: string;
  short_description: string | null;
  location: string;
  gender?: string;
  vacancy: number;
  salary_min: string | null;
  salary_max: string | null;
  salary_type: string;
  application_deadline: string | null;
  featured_image: string | null;
  is_featured: boolean;
  department?: { id: number; name: string };
  employment_type?: { id: number; name: string };
}

const getImageUrl = (url: string | null) => {
  if (!url) return '';
  if (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('/')) {
    return url;
  }
  return '/' + url;
};

const formatDate = (dateStr: string | null) => {
  if (!dateStr) return '';
  try {
    if (dateStr.includes('T')) {
      return dateStr.split('T')[0];
    }
    return dateStr;
  } catch (e) {
    return dateStr;
  }
};

interface FilterData {
  locations: string[];
  departments: { id: number; name: string }[];
  employmentTypes: { id: number; name: string }[];
}

export const CareerPage: React.FC = () => {
  const [jobs, setJobs] = useState<Job[]>([]);
  
  // Pagination
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  
  const [loading, setLoading] = useState(true);

  const fetchJobs = async () => {
    setLoading(true);
    try {
      const res = await fetch(`/api/careers?page=${currentPage}`);
      if (res.ok) {
        const data = await res.json();
        setJobs(data.jobs.data || []);
        setTotalPages(data.jobs.last_page || 1);
      }
    } catch (err) {
      console.error("Failed to load jobs list", err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchJobs();
  }, [currentPage]);

  return (
    <div className="w-full bg-salon-black min-h-screen text-white pt-24 pb-20 relative overflow-hidden">
      {/* Background glow accents */}
      <div className="absolute top-[10%] left-[-15%] h-[600px] w-[600px] rounded-full bg-gold-400/5 blur-[150px] pointer-events-none"></div>
      <div className="absolute bottom-[10%] right-[-15%] h-[600px] w-[600px] rounded-full bg-gold-400/5 blur-[150px] pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 md:px-8 relative z-10 space-y-12">
        {/* Header section */}
        <div className="text-center max-w-3xl mx-auto space-y-4">
          <span className="text-[10px] font-mono tracking-[0.4em] text-gold-400 uppercase block gold-glow">
            Adonis Recruitment Guild
          </span>
          <h1 className="font-serif text-4xl sm:text-5xl uppercase tracking-wider text-white">
            Build Your Legacy
          </h1>
          <p className="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
            We invite master stylists, hospitality experts, and concierge staff who appreciate perfection to join our luxury men's grooming lounges in Dhaka.
          </p>
        </div>

        {/* Listings Display */}
        {loading ? (
          <div className="text-center py-20">
            <div className="inline-block animate-spin rounded-full h-8 w-8 border-2 border-t-gold-400 border-white/10"></div>
            <p className="text-xs text-gray-500 font-mono uppercase tracking-widest mt-4">Loading active openings...</p>
          </div>
        ) : jobs.length === 0 ? (
          <div className="bg-[#141414] border border-white/5 py-16 text-center space-y-3">
            <LucideIcon name="Briefcase" className="text-gray-600 mx-auto" size={40} />
            <h3 className="font-serif text-base uppercase tracking-wider text-white">No Career Openings Found</h3>
            <p className="text-xs text-gray-500 max-w-md mx-auto">
              There are currently no active job postings matching your filter selections. Reset search criteria or register interest for future openings.
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {jobs.map((job) => (
              <div
                key={job.id}
                className="bg-[#141414] border border-white/5 hover:border-gold-400/25 transition-all duration-300 flex flex-col justify-between group relative"
              >
                {job.is_featured && (
                  <span className="absolute top-3 left-3 bg-gold-400 text-black text-[8px] font-mono uppercase tracking-widest px-2 py-0.5 font-bold z-10">
                    Featured
                  </span>
                )}
                
                <div>
                  {/* Card Thumbnail */}
                  <div className="w-full overflow-hidden bg-salon-gray relative border-b border-white/5">
                    {job.featured_image ? (
                      <img
                        src={getImageUrl(job.featured_image)}
                        alt={job.title}
                        className="w-full h-auto block object-contain object-center group-hover:scale-[1.03] transition-all duration-700 brightness-95"
                      />
                    ) : (
                      <div className="w-full aspect-[16/9] flex items-center justify-center bg-[#1c1c1c] text-gold-400/10">
                        <LucideIcon name="Scissors" size={48} />
                      </div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-salon-black via-transparent to-transparent opacity-40 pointer-events-none"></div>
                  </div>

                  {/* Card Content */}
                  <div className="p-6 space-y-4">
                    <div>
                      <span className="text-[8px] font-mono text-gold-400 uppercase tracking-widest block font-bold">
                        {job.department ? job.department.name : 'ADONIS GENERAL'} • {job.employment_type ? job.employment_type.name : 'FULL TIME'}
                      </span>
                      <h4 className="font-serif text-sm uppercase tracking-wider text-white mt-1 group-hover:text-gold-400 transition-colors line-clamp-1">
                        {job.title}
                      </h4>
                    </div>

                    <p className="text-[11px] text-gray-400 line-clamp-3 font-sans leading-relaxed">
                      {job.short_description || 'No description provided. Click details to learn more.'}
                    </p>

                    <div className="pt-2 border-t border-white/5 grid grid-cols-2 gap-y-2 text-[10px] font-mono text-gray-500 uppercase tracking-wider">
                      <div className="flex items-center gap-1.5">
                        <LucideIcon name="MapPin" size={10} className="text-gold-400" />
                        <span className="truncate">{job.location}</span>
                      </div>
                      <div className="flex items-center gap-1.5 justify-end">
                        <LucideIcon name="User" size={10} className="text-gold-400" />
                        <span>Gender: {job.gender || 'Both'}</span>
                      </div>
                      <div className="flex items-center gap-1.5">
                        <LucideIcon name="DollarSign" size={10} className="text-gold-400" />
                        <span>
                          {job.salary_min && job.salary_max
                            ? `${parseInt(job.salary_min)} - ${parseInt(job.salary_max)}`
                            : job.salary_type}
                        </span>
                      </div>
                      {job.application_deadline && (
                        <div className="flex items-center gap-1.5 justify-end">
                          <LucideIcon name="Calendar" size={10} className="text-gold-400" />
                          <span>End: {formatDate(job.application_deadline)}</span>
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {/* Apply Button */}
                <div className="p-6 pt-0">
                  <button
                    onClick={() => navigateTo(`/career/${job.slug}`)}
                    className="w-full py-2.5 bg-[#32BBED] hover:bg-[#b08d3c] text-black font-serif text-[10px] font-bold uppercase tracking-widest transition-all duration-300 cursor-pointer text-center"
                  >
                    Apply Now
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Pagination Controls */}
        {totalPages > 1 && (
          <div className="flex justify-center items-center gap-4 pt-6 text-xs font-mono uppercase tracking-widest">
            <button
              onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
              disabled={currentPage === 1}
              className="px-4 py-2 border border-white/10 text-gray-400 disabled:opacity-30 disabled:pointer-events-none hover:border-gold-400 hover:text-gold-400 transition-colors"
            >
              Prev
            </button>
            <span className="text-white">Page {currentPage} of {totalPages}</span>
            <button
              onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
              disabled={currentPage === totalPages}
              className="px-4 py-2 border border-white/10 text-gray-400 disabled:opacity-30 disabled:pointer-events-none hover:border-gold-400 hover:text-gold-400 transition-colors"
            >
              Next
            </button>
          </div>
        )}
      </div>
    </div>
  );
};
