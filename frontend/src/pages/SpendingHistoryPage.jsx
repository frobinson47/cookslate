import React, { useEffect, useMemo } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { Receipt, ArrowLeft, Trash2 } from 'lucide-react';
import useShoppingTrips from '../hooks/useShoppingTrips';
import useDocumentTitle from '../hooks/useDocumentTitle';
import { categorizeIngredient } from '../utils/ingredientCategories';
import { Skeleton } from '../components/ui/Skeleton';
import EmptyState from '../components/ui/EmptyState';
import Button from '../components/ui/Button';

function formatMoney(value) {
  return value == null ? '—' : `$${Number(value).toFixed(2)}`;
}

function TripListItem({ t }) {
  return (
    <Link
      to={`/spending-history/${t.id}`}
      className="flex items-center justify-between p-4 bg-surface rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-200"
    >
      <div>
        <p className="font-semibold text-brown">{t.store_name || 'Unknown store'}</p>
        <p className="text-sm text-warm-gray">{t.trip_date || 'No date'}</p>
      </div>
      <p className="font-bold text-terracotta">{formatMoney(t.total_amount)}</p>
    </Link>
  );
}

function TripDetail({ trip, onDelete }) {
  const navigate = useNavigate();

  const categoryBreakdown = useMemo(() => {
    const totals = {};
    for (const item of trip.items || []) {
      if (item.price == null) continue;
      const category = categorizeIngredient(item.item_name);
      totals[category] = (totals[category] || 0) + Number(item.price);
    }
    return Object.entries(totals).sort(([, a], [, b]) => b - a);
  }, [trip]);

  const handleDelete = async () => {
    if (!window.confirm('Delete this shopping trip?')) return;
    await onDelete(trip.id);
    navigate('/spending-history');
  };

  return (
    <div className="space-y-6">
      <div className="bg-surface rounded-2xl shadow-md p-6">
        <div className="flex items-start justify-between">
          <div>
            <h2 className="text-xl font-bold text-brown">{trip.store_name || 'Unknown store'}</h2>
            <p className="text-sm text-warm-gray">{trip.trip_date || 'No date'}</p>
          </div>
          <p className="text-2xl font-bold text-terracotta">{formatMoney(trip.total_amount)}</p>
        </div>
      </div>

      {categoryBreakdown.length > 0 && (
        <div className="bg-surface rounded-2xl shadow-md p-6">
          <h3 className="font-semibold text-brown mb-3">By Category</h3>
          <div className="space-y-2">
            {categoryBreakdown.map(([category, total]) => (
              <div key={category} className="flex items-center justify-between text-sm">
                <span className="text-brown-light">{category}</span>
                <span className="text-warm-gray">{formatMoney(total)}</span>
              </div>
            ))}
          </div>
        </div>
      )}

      <div className="bg-surface rounded-2xl shadow-md p-6">
        <h3 className="font-semibold text-brown mb-3">Items</h3>
        <div className="space-y-2">
          {(trip.items || []).map((item) => (
            <div key={item.id} className="flex items-center justify-between text-sm">
              <span className="text-brown-light">
                {item.item_name}
                {item.quantity != null && (
                  <span className="text-warm-gray"> · {item.quantity}{item.unit ? ` ${item.unit}` : ''}</span>
                )}
              </span>
              <span className="text-warm-gray">{formatMoney(item.price)}</span>
            </div>
          ))}
        </div>
      </div>

      <Button variant="danger" size="sm" onClick={handleDelete}>
        <Trash2 size={16} />
        Delete Trip
      </Button>
    </div>
  );
}

export default function SpendingHistoryPage() {
  useDocumentTitle('Spending History');

  const { id } = useParams();
  const { trips, trip, isLoading, fetchTrips, fetchTrip, removeTrip } = useShoppingTrips();

  useEffect(() => {
    if (id) {
      fetchTrip(id);
    } else {
      fetchTrips();
    }
  }, [id, fetchTrip, fetchTrips]);

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-brown flex items-center gap-2">
          <Receipt size={24} className="text-terracotta" />
          {id ? 'Trip Details' : 'Spending History'}
        </h1>
        {id ? (
          <Link to="/spending-history" className="text-sm text-warm-gray hover:text-brown transition-colors flex items-center gap-1">
            <ArrowLeft size={14} />
            All Trips
          </Link>
        ) : (
          <Link to="/scan-receipt" className="text-sm text-terracotta hover:underline">
            Scan Receipt
          </Link>
        )}
      </div>

      {isLoading && (
        <div className="space-y-3">
          <Skeleton className="h-20 rounded-2xl" />
          <Skeleton className="h-20 rounded-2xl" />
        </div>
      )}

      {!isLoading && id && trip && <TripDetail trip={trip} onDelete={removeTrip} />}

      {!isLoading && !id && trips.length === 0 && (
        <EmptyState
          icon={Receipt}
          title="No trips yet"
          description="Scan a receipt to start tracking your spending and refill your pantry."
          actionLabel="Scan Receipt"
          actionTo="/scan-receipt"
        />
      )}

      {!isLoading && !id && trips.length > 0 && (
        <div className="space-y-3">
          {trips.map((t) => (
            <TripListItem key={t.id} t={t} />
          ))}
        </div>
      )}
    </div>
  );
}
