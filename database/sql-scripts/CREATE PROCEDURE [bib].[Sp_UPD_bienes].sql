-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [bib].[Sp_UPD_bienes]

    @_iBienId INTEGER,
    @_iGrupoBienesId INTEGER,
    @_iLocalId INTEGER,
    @_iUbicaBienId INTEGER,
    @_cCodPatrimonial VARCHAR(20),
    @_cISBN VARCHAR(20),
    @_cISSN VARCHAR(20),
    @_iTipoBienId INTEGER,
    @_iClasificacionMaterialId INTEGER,
    @_iClasiMaterialDetId INTEGER,
    @_cTitulo VARCHAR(200),
    @_cMateriaTema VARCHAR(200),
    @_iAutorId INTEGER,
    @_cVolumenTomo VARCHAR(50),
    @_cIncluye VARCHAR(30),
    @_iAnhoPublicacion INTEGER,
    @_cNumeroEdicion VARCHAR(25),
    @_iAnhoEdicion INTEGER,
    @_iEditorialId INTEGER,
    @_cCiudad VARCHAR(60),
    @_cPais VARCHAR(60),
    @_iNumPaginas INTEGER,
    @_iEstadoBienId INTEGER,
    @_iCarreraId INTEGER,
    --@_dFechaBaja DATETIME,
    --@_dFechaIngreso DATETIME,
    --@_dFechaEgreso DATETIME,
    @_cObservaciones VARCHAR(MAX),
    @_cDescripcion_Mat VARCHAR(80),
    @_cSerial_Mat VARCHAR(20),
    @_cModelo_Mat VARCHAR(20),
    @_cMarca_Mat VARCHAR(15),
    @_cColor VARCHAR(10),
    @_cPortada VARCHAR(MAX),
    @_bHabilitado BIT,

    @_cUsuarioSis VARCHAR(50),
    @_cEquipoSis VARCHAR(50),
    @_cIpSis VARCHAR(15),
    @_cMacNicSis VARCHAR(35)
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @cUsuarioSis VARCHAR(50)
    SELECT @cUsuarioSis=c.cCredUsuario
    FROM seg.credenciales AS c
    WHERE c.iCredId=@_cUsuarioSis
    IF @@ERROR<>0 GOTO ErrorCapturado

    UPDATE bib.bienes
	SET

	   iGrupoBienesId=@_iGrupoBienesId,
       iLocalId=@_iLocalId,
	   iUbicaBienId=@_iUbicaBienId,
	   cCodPatrimonial=@_cCodPatrimonial,
       cISBN=@_cISBN,
       cISSN=@_cISSN,
       iTipoBienId=@_iTipoBienId,
       iClasificacionMaterialId=@_iClasificacionMaterialId,
	   iClasiMaterialDetId=@_iClasiMaterialDetId,
       cTitulo=@_cTitulo,
       cMateriaTema=@_cMateriaTema,
       iAutorId=@_iAutorId,
       cVolumenTomo=@_cVolumenTomo,
       cIncluye=@_cIncluye,
       iAnhoPublicacion=@_iAnhoPublicacion,
       cNumeroEdicion=@_cNumeroEdicion,
       iAnhoEdicion=@_iAnhoEdicion,
       iEditorialId=@_iEditorialId,
       cCiudad=@_cCiudad,
       cPais=@_cPais,
       iNumPaginas=@_iNumPaginas,
       iEstadoBienId=@_iEstadoBienId,
       iCarreraId=@_iCarreraId,
       --dFechaBaja=@_dFechaBaja,
       --dFechaIngreso=@_dFechaIngreso,
       --dFechaEgreso=@_dFechaEgreso,
       cObservaciones=@_cObservaciones,
       cDescripcion_Mat=@_cDescripcion_Mat,
       cSerial_Mat=@_cSerial_Mat,
       cModelo_Mat=@_cModelo_Mat,
       cMarca_Mat=@_cMarca_Mat,
       cColor=@_cColor,
       cPortada=@_cPortada,
       bHabilitado=@_bHabilitado,

		cUsuarioSis=@_cUsuarioSis,
		dFechaSis=GETDATE(),
		cEquipoSis=@_cEquipoSis,
		cIpSis=@_cIpSis,
		cOpenUsr='E',
		cMacNicSis=@_cMacNicSis

	WHERE iBienId=@_iBienId
    IF @@ERROR<>0 GOTO ErrorCapturado

    COMMIT TRANSACTION
    SELECT 1 AS iResult
    RETURN 1
    ErrorCapturado:
    ROLLBACK TRANSACTION
    SELECT 0 AS iResult
    RETURN 0


END
GO
