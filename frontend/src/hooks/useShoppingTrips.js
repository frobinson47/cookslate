import { useState, useCallback } from 'react';
import * as api from '../services/api';

export function useShoppingTrips() {
  const [trips, setTrips] = useState([]);
  const [trip, setTrip] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchTrips = useCallback(async () => {
    setIsLoading(true);
    setError(null);
    try {
      const data = await api.getShoppingTrips();
      setTrips(data.trips || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setIsLoading(false);
    }
  }, []);

  const fetchTrip = useCallback(async (id) => {
    setIsLoading(true);
    setError(null);
    try {
      const data = await api.getShoppingTrip(id);
      setTrip(data);
      return data;
    } catch (err) {
      setError(err.message);
      return null;
    } finally {
      setIsLoading(false);
    }
  }, []);

  const importReceipt = useCallback(async (imageFile) => {
    setIsLoading(true);
    setError(null);
    try {
      return await api.importReceipt(imageFile);
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, []);

  const createTrip = useCallback(async (tripData) => {
    setIsLoading(true);
    setError(null);
    try {
      return await api.createShoppingTrip(tripData);
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, []);

  const removeTrip = useCallback(async (id) => {
    setError(null);
    try {
      await api.deleteShoppingTrip(id);
      setTrips(prev => prev.filter(t => t.id !== id));
    } catch (err) {
      setError(err.message);
      throw err;
    }
  }, []);

  return { trips, trip, isLoading, error, fetchTrips, fetchTrip, importReceipt, createTrip, removeTrip };
}

export default useShoppingTrips;
