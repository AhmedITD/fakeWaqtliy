const data = {
    neighborhoodsData: {
      'type': 'FeatureCollection',
      'features': [
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4376, 33.3152] // Al Rusafa
        },
        'properties': {
          'name': 'Al Rusafa',
          'title': 'Al Rusafa District',
          'type': 'district',
          'description': 'Historic district on the east bank of the Tigris River, home to many government buildings and cultural sites',
          'population': '1,500,000',
          'area': 'East Baghdad',
          'landmarks': 'Abbasid Palace, Baghdad Museum, Al Mustansiriya University'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3776, 33.3152] // Al Karkh
        },
        'properties': {
          'name': 'Al Karkh',
          'title': 'Al Karkh District',
          'type': 'district',
          'description': 'Historic district on the west bank of the Tigris River, known for its commercial areas and residential neighborhoods',
          'population': '1,800,000',
          'area': 'West Baghdad',
          'landmarks': 'Green Zone, Iraqi Parliament, Al Zawra Park'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4156, 33.3428] // Sadr City
        },
        'properties': {
          'name': 'Sadr City',
          'title': 'Sadr City (Thawra)',
          'type': 'district',
          'description': 'Large residential area in northeast Baghdad, one of the most populous districts',
          'population': '2,500,000',
          'area': 'Northeast Baghdad',
          'landmarks': 'Sadr City Hospital, Al Thawra Stadium'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3456, 33.3352] // Kadhimiya
        },
        'properties': {
          'name': 'Kadhimiya',
          'title': 'Kadhimiya District',
          'type': 'district',
          'description': 'Religious district known for the shrine of Imam Musa al-Kadhim and Imam Muhammad al-Jawad',
          'population': '500,000',
          'area': 'North Baghdad',
          'landmarks': 'Kadhimiya Shrine, Al Kadhimiya Mosque'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4676, 33.2952] // New Baghdad
        },
        'properties': {
          'name': 'New Baghdad',
          'title': 'New Baghdad (Baghdad Jadida)',
          'type': 'district',
          'description': 'Modern residential and commercial district in southeast Baghdad',
          'population': '800,000',
          'area': 'Southeast Baghdad',
          'landmarks': 'Baghdad Mall, University of Technology'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3256, 33.3052] // Al Mansour
        },
        'properties': {
          'name': 'Al Mansour',
          'title': 'Al Mansour District',
          'type': 'district',
          'description': 'Upscale residential and commercial district in western Baghdad',
          'population': '600,000',
          'area': 'West Baghdad',
          'landmarks': 'Al Mansour Mall, Baghdad University Medical City'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4056, 33.2752] // Dora
        },
        'properties': {
          'name': 'Dora',
          'title': 'Dora District',
          'type': 'district',
          'description': 'Industrial and residential district in southern Baghdad',
          'population': '700,000',
          'area': 'South Baghdad',
          'landmarks': 'Dora Refinery, Industrial Complex'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4456, 33.3552] // Adhamiya
        },
        'properties': {
          'name': 'Adhamiya',
          'title': 'Adhamiya District',
          'type': 'district',
          'description': 'Historic Sunni-majority district known for Abu Hanifa Mosque',
          'population': '500,000',
          'area': 'North Baghdad',
          'landmarks': 'Abu Hanifa Mosque, Adhamiya Bridge'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3856, 33.3352] // Karrada
        },
        'properties': {
          'name': 'Karrada',
          'title': 'Karrada District',
          'type': 'district',
          'description': 'Central commercial and residential district, popular shopping area',
          'population': '400,000',
          'area': 'Central Baghdad',
          'landmarks': 'Karrada Shopping District, Babylon Hotel'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3656, 33.2852] // Bayaa
        },
        'properties': {
          'name': 'Bayaa',
          'title': 'Bayaa District',
          'type': 'district',
          'description': 'Residential district in southwestern Baghdad',
          'population': '300,000',
          'area': 'Southwest Baghdad',
          'landmarks': 'Bayaa Market, Local Schools'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4856, 33.3252] // Palestine Street
        },
        'properties': {
          'name': 'Palestine Street',
          'title': 'Palestine Street Area',
          'type': 'neighborhood',
          'description': 'Major commercial street and surrounding residential area',
          'population': '200,000',
          'area': 'East Baghdad',
          'landmarks': 'Palestine Street Market, Medical Centers'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3456, 33.2652] // Abu Ghraib
        },
        'properties': {
          'name': 'Abu Ghraib',
          'title': 'Abu Ghraib District',
          'type': 'district',
          'description': 'Western district of Baghdad, agricultural area',
          'population': '400,000',
          'area': 'West Baghdad',
          'landmarks': 'Agricultural Areas, Local Markets'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4256, 33.3752] // Taji
        },
        'properties': {
          'name': 'Taji',
          'title': 'Taji District',
          'type': 'district',
          'description': 'Northern district of Baghdad, industrial area',
          'population': '350,000',
          'area': 'North Baghdad',
          'landmarks': 'Taji Industrial Complex, Military Base'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4576, 33.2552] // Mahmudiyah
        },
        'properties': {
          'name': 'Mahmudiyah',
          'title': 'Mahmudiyah District',
          'type': 'district',
          'description': 'Southern district of Baghdad, agricultural and residential',
          'population': '300,000',
          'area': 'South Baghdad',
          'landmarks': 'Agricultural Fields, Local Markets'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.3956, 33.3252] // Bab al-Sharqi
        },
        'properties': {
          'name': 'Bab al-Sharqi',
          'title': 'Bab al-Sharqi (Eastern Gate)',
          'type': 'neighborhood',
          'description': 'Historic neighborhood in central Baghdad, cultural area',
          'population': '150,000',
          'area': 'Central Baghdad',
          'landmarks': 'Al Mutanabbi Street, Historic Buildings'
        }
      },
      {
        'type': 'Feature',
        'geometry': {
          'type': 'Point',
          'coordinates': [44.4376, 33.3016] // Makers of Baghdad (keeping original)
        },
        'properties': {
          'phoneFormatted': '+964 783 491 5325',
          'phone': '9647834915325',
          'address': 'Al Sina\'a Street, Al Rusafa',
          'city': 'Baghdad',
          'country': 'Iraq',
          'name': 'Makers of Baghdad',
          'title': 'Makers of Baghdad Innovation Hub',
          'type': 'coworking',
          'description': 'Innovation hub and makerspace with 3D printers, laser cutters, and startup incubation programs',
          'capacity': '80 people',
          'amenities': '3D Printers, Laser Cutters, Fabrication Lab, Training Programs',
          'website': 'https://makersiq.org/',
          'email': 'ali.taher@iotmaker.org',
          'image': 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=300&h=200&fit=crop'
        }
      }
    ]
    },
    query: ''
  }

export default data