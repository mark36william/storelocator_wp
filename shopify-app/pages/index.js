import React, { useState, useEffect } from 'react';
import {
  Page,
  Layout,
  Card,
  Button,
  TextField,
  DataTable
} from '@shopify/polaris';
import { useAppBridge } from '@shopify/app-bridge-react';
import { getSessionToken } from "@shopify/app-bridge-utils";

export default function Index() {
  const app = useAppBridge();
  const [locations, setLocations] = useState([]);
  const [newLocation, setNewLocation] = useState({
    name: '',
    address: '',
    lat: '',
    lng: '',
    description: ''
  });

  useEffect(() => {
    fetchLocations();
  }, []);

  const fetchLocations = async () => {
    const token = await getSessionToken(app);
    const response = await fetch('/api/locations', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    const data = await response.json();
    setLocations(data);
  };

  const handleSubmit = async () => {
    const token = await getSessionToken(app);
    await fetch('/api/locations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify(newLocation)
    });
    fetchLocations();
    setNewLocation({
      name: '',
      address: '',
      lat: '',
      lng: '',
      description: ''
    });
  };

  const rows = locations.map((location) => [
    location.name,
    location.address,
    location.lat,
    location.lng,
    location.description
  ]);

  return (
    <Page title="Store Locator">
      <Layout>
        <Layout.Section>
          <Card sectioned>
            <TextField
              label="Store Name"
              value={newLocation.name}
              onChange={(value) => setNewLocation({ ...newLocation, name: value })}
            />
            <TextField
              label="Address"
              value={newLocation.address}
              onChange={(value) => setNewLocation({ ...newLocation, address: value })}
            />
            <TextField
              label="Latitude"
              type="number"
              value={newLocation.lat}
              onChange={(value) => setNewLocation({ ...newLocation, lat: value })}
            />
            <TextField
              label="Longitude"
              type="number"
              value={newLocation.lng}
              onChange={(value) => setNewLocation({ ...newLocation, lng: value })}
            />
            <TextField
              label="Description"
              multiline
              value={newLocation.description}
              onChange={(value) => setNewLocation({ ...newLocation, description: value })}
            />
            <Button primary onClick={handleSubmit}>Add Location</Button>
          </Card>
        </Layout.Section>

        <Layout.Section>
          <Card>
            <DataTable
              columnContentTypes={['text', 'text', 'numeric', 'numeric', 'text']}
              headings={['Name', 'Address', 'Latitude', 'Longitude', 'Description']}
              rows={rows}
            />
          </Card>
        </Layout.Section>
      </Layout>
    </Page>
  );
}
