{
        name: 'Citas atendidas',
        data: [49, 71, 106]
		       
    },{
    		name: 'Citas canceladas',
        data: [6, 7, 4]
    }

    factory(Appointments::class, 50)->states('patient')->create();